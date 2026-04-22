<?php

namespace App\Http\Controllers;

use App\Models\ClassTiming;
use App\Models\ModuleDistribution;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ClassTimingManagementController extends Controller
{
    public function index(Request $request)
    {
        $classTimings = ClassTiming::with(['moduleDistribution.module.program', 'week'])
            ->whereHas('moduleDistribution.module.program', function ($query) {
                $query->where('department_id', $this->hodDepartmentId());
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('day', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%")
                        ->orWhere('room', 'like', "%{$search}%")
                        ->orWhereHas('moduleDistribution.module', fn ($moduleQuery) => $moduleQuery->where('module_name', 'like', "%{$search}%"));
                });
            })
            ->paginate(10);

        return view('management.class_timings.index', compact('classTimings'));
    }

    public function export(Request $request)
    {
        $classTimings = ClassTiming::with(['moduleDistribution.module.program', 'week'])
            ->whereHas('moduleDistribution.module.program', function ($query) {
                $query->where('department_id', $this->hodDepartmentId());
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('day', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%")
                        ->orWhere('room', 'like', "%{$search}%")
                        ->orWhereHas('moduleDistribution.module', fn ($moduleQuery) => $moduleQuery->where('module_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('day')
            ->orderBy('time')
            ->get();

        return response()->json([
            'sheet_name' => 'Class Timings',
            'filename' => 'class-timings-export.xlsx',
            'rows' => $classTimings->map(fn ($classTiming) => [
                'module_distribution_id' => $classTiming->module_distribution_id,
                'module_code' => $classTiming->moduleDistribution?->module?->module_code,
                'academic_year' => $classTiming->moduleDistribution?->academic_year,
                'day' => $classTiming->day,
                'time' => $classTiming->time,
                'room' => $classTiming->room,
                'week_name' => $classTiming->week?->week_name,
                'week_id' => $classTiming->week_id,
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('management.class_timings.create', $this->formData());
    }

    public function store(Request $request)
    {
        ClassTiming::create($this->validatedData($request));

        return redirect()->route('class-timings.index')->with('success', 'Timetable entry created successfully.');
    }

    public function edit(ClassTiming $class_timing)
    {
        $this->authorizeClassTiming($class_timing);

        return view('management.class_timings.edit', array_merge(
            $this->formData(),
            ['classTiming' => $class_timing]
        ));
    }

    public function update(Request $request, ClassTiming $class_timing)
    {
        $this->authorizeClassTiming($class_timing);

        $class_timing->update($this->validatedData($request));

        return redirect()->route('class-timings.index')->with('success', 'Timetable entry updated successfully.');
    }

    public function destroy(ClassTiming $class_timing)
    {
        $this->authorizeClassTiming($class_timing);

        $class_timing->delete();

        return redirect()->route('class-timings.index')->with('success', 'Timetable entry deleted successfully.');
    }

    private function formData(): array
    {
        return [
            'moduleDistributions' => ModuleDistribution::with('module')
                ->whereHas('module.program', function ($query) {
                    $query->where('department_id', $this->hodDepartmentId());
                })
                ->get(),
            'weeks' => $this->hasWeekColumn() ? Week::orderBy('id')->get() : collect(),
            'hasWeek' => $this->hasWeekColumn(),
            'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ];
    }

    private function validatedData(Request $request): array
    {
        $rules = [
            'module_distribution_id' => 'required|exists:module_distributions,id',
            'day' => 'required|string|max:20',
            'time' => 'required|string|max:50',
            'room' => 'required|string|max:255',
        ];

        if ($this->hasWeekColumn()) {
            $rules['week_id'] = 'nullable|exists:weeks,id';
        }

        $validated = $request->validate($rules);

        abort_unless(
            ModuleDistribution::where('id', $validated['module_distribution_id'])
                ->whereHas('module.program', function ($query) {
                    $query->where('department_id', $this->hodDepartmentId());
                })->exists(),
            403
        );

        return $validated;
    }

    private function authorizeClassTiming(ClassTiming $classTiming): void
    {
        abort_unless(
            ModuleDistribution::where('id', $classTiming->module_distribution_id)
                ->whereHas('module.program', function ($query) {
                    $query->where('department_id', $this->hodDepartmentId());
                })->exists(),
            403
        );
    }

    private function hodDepartmentId(): ?int
    {
        return auth()->user()->hod->department_id ?? auth()->user()->department_id;
    }

    private function hasWeekColumn(): bool
    {
        return Schema::hasColumn('class_timings', 'week_id');
    }
}
