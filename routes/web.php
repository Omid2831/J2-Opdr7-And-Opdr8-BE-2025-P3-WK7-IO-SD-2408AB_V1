<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rijschoolhouder\InstructorController;
use App\Http\Controllers\Rijschoolhouder\InstructorVehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = request()->user();

    if ($user?->isRijschoolhouder() && Route::has('rijschoolhouder.dashboard')) {
        return redirect()->route('rijschoolhouder.dashboard');
    }

    if ($user?->isUser() && Route::has('user.dashboard')) {
        return redirect()->route('user.dashboard');
    }

    return view('user.dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/rijschoolhouder/dashboard', function () {
    return redirect()->route('rijschoolhouder.instructors.index');
})->middleware(['auth', 'role:rijschoolhouder'])->name('rijschoolhouder.dashboard');

Route::middleware(['auth', 'role:rijschoolhouder'])
    ->prefix('rijschoolhouder')
    ->name('rijschoolhouder.')
    ->group(function () {
        Route::get('/alle-voertuigen', [InstructorVehicleController::class, 'allVehicles'])
            ->name('vehicles.all');
        Route::get('/instructeurs', [InstructorController::class, 'index'])
            ->name('instructors.index');
        Route::get('/instructeurs/{instructor}/voertuigen', [InstructorVehicleController::class, 'index'])
            ->name('instructors.vehicles.index');
        Route::get(
            '/instructeurs/{instructor}/alle-beschikbare-autos',
            [InstructorVehicleController::class, 'availableVehicles']
        )->name('instructors.vehicles.available');
        Route::get('/voertuigen/{vehicle}/wijzigen', [InstructorVehicleController::class, 'edit'])
            ->whereNumber('vehicle')
            ->name('vehicles.edit');
        Route::post('/instructeurs/{instructor}/voertuig-toevoegen', [InstructorVehicleController::class, 'addVehicle'])
            ->name('instructors.vehicles.add');
        Route::post('/instructeurs/{instructor}/voertuig-toevoegen-en-wijzigen', [InstructorVehicleController::class, 'addVehicleAndEdit'])
            ->name('instructors.vehicles.addAndEdit');
        Route::put('/voertuigen/{vehicle}', [InstructorVehicleController::class, 'update'])
            ->whereNumber('vehicle')
            ->name('vehicles.update');
        Route::delete('/voertuigen/{vehicle}/verwijderen', [InstructorVehicleController::class, 'destroy'])
            ->whereNumber('vehicle')
            ->name('instructors.vehicles.destroy');
    });

Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth', 'role:user'])->name('user.dashboard');

Route::get('/loadbar', function (Request $request) {
    $user = $request->user();
    $redirectTo = $request->query('redirectTo');

    if (! $redirectTo) {
        $intended = session('url.intended');
        $loadbarUrl = route('loadbar');

        if ($intended && $intended !== $loadbarUrl) {
            $redirectTo = $intended;
        } else {
            $redirectTo = match (true) {
                $user?->isRijschoolhouder() && Route::has('rijschoolhouder.dashboard') => route('rijschoolhouder.dashboard'),
                $user?->isUser() && Route::has('user.dashboard') => route('user.dashboard'),
                default => route('dashboard'),
            };
        }
    }

    session()->forget('url.intended');

    return view('loadbar', [
        'redirectTo' => $redirectTo,
        'delayMs' => 3000,
        'message' => $request->query('message'),
    ]);
})->middleware('auth')->name('loadbar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
