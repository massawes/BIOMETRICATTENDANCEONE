<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Program;
use App\Models\Student;

class RegistrarController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalPrograms = Program::count();
        $totalDepartments = Department::count();

        return view('dashboards.Registrar', compact(
            'totalStudents',
            'totalPrograms',
            'totalDepartments'
        ));
    }
}
