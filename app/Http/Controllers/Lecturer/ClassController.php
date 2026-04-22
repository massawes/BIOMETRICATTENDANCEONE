<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModuleDistribution; 

class ClassController extends Controller
{
    //
    public function index()
    {
        $data = ModuleDistribution::where('user_id', auth()->id())
                    ->with('module.program') // 🔥 important
                    ->get();

        return view('lecturer.classes', compact('data'));
    }
}
