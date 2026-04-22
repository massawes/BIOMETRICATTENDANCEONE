<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class RectorController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalLecturers = Lecturer::count();

        $totalPresent = Attendance::where('is_present', 1)->count();
        $totalRecords = Attendance::count();
        $attendanceRate = $totalRecords > 0 ? round(($totalPresent / $totalRecords) * 100, 1) : 0;

        $departmentPerformance = DB::table('departments as d')
            ->leftJoin('programs as p', 'd.id', '=', 'p.department_id')
            ->leftJoin('students as s', 'p.id', '=', 's.program_id')
            ->leftJoin('attendances as a', 's.id', '=', 'a.student_id')
            ->select(
                'd.id',
                'd.department_name',
                DB::raw('COUNT(DISTINCT p.id) as total_programs'),
                DB::raw('COUNT(DISTINCT s.id) as total_students'),
                DB::raw('COUNT(a.id) as total_records'),
                DB::raw('SUM(CASE WHEN a.is_present = 1 THEN 1 ELSE 0 END) as present_records'),
                DB::raw('ROUND(CASE WHEN COUNT(a.id) = 0 THEN 0 ELSE (SUM(CASE WHEN a.is_present = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(a.id)) END, 1) as attendance_rate')
            )
            ->groupBy('d.id', 'd.department_name')
            ->orderByDesc('attendance_rate')
            ->take(3)
            ->get();

        return view('dashboards.Rector', compact(
            'totalStudents',
            'totalLecturers',
            'attendanceRate',
            'departmentPerformance'
        ));
    }
}
