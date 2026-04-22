<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\ImportZkbioStudents;
use App\Console\Commands\SyncZkbioAttendance;
use App\Http\Middleware\CheckHod;
use App\Http\Middleware\CheckRolLecturer;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        ImportZkbioStudents::class,
        SyncZkbioAttendance::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'hod' => CheckHod::class,
            'rolelecturer' => CheckRolLecturer::class,
            'role'=> CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
