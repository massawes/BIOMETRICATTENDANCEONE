<?php

namespace App\Http\Controllers;

use App\Models\Week;
use Illuminate\Http\Request;

class WeekManagementController extends Controller
{
    public function index(Request $request)
    {
        $weeks = Week::when($request->filled('search'), function ($query) use ($request) {
            $query->where('week_name', 'like', '%' . $request->search . '%');
        })->paginate(10);

        return view('management.weeks.index', compact('weeks'));
    }

    public function export(Request $request)
    {
        $weeks = Week::when($request->filled('search'), function ($query) use ($request) {
            $query->where('week_name', 'like', '%' . $request->search . '%');
        })
            ->orderBy('week_name')
            ->get();

        return response()->json([
            'sheet_name' => 'Weeks',
            'filename' => 'weeks-export.xlsx',
            'rows' => $weeks->map(fn ($week) => [
                'week_name' => $week->week_name,
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('management.weeks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'week_name' => 'required|string|max:255|unique:weeks,week_name',
        ]);

        Week::create($validated);

        return redirect()->route('weeks.index')->with('success', 'Week created successfully.');
    }

    public function edit(Week $week)
    {
        return view('management.weeks.edit', compact('week'));
    }

    public function update(Request $request, Week $week)
    {
        $validated = $request->validate([
            'week_name' => 'required|string|max:255|unique:weeks,week_name,' . $week->id,
        ]);

        $week->update($validated);

        return redirect()->route('weeks.index')->with('success', 'Week updated successfully.');
    }

    public function destroy(Week $week)
    {
        $week->delete();

        return redirect()->route('weeks.index')->with('success', 'Week deleted successfully.');
    }
}
