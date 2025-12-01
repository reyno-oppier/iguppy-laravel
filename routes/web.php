<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeederController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\UserController;

// Home → redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // redirect explicitly to login
})->name('logout');

Route::put('/account', [AccountController::class, 'update'])->name('account.update');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/predict', function () {
    $python = base_path('venv/Scripts/python.exe'); // full path to python.exe in your venv
    $script = base_path('ml/predict.py');

    $output = [];
    $returnVar = 0;

    exec("\"$python\" \"$script\" 1", $output, $returnVar);

    $json = @json_decode(implode("\n", $output), true);

    if ($returnVar !== 0 || !$json) {
        return response()->json([
            'error' => 'Python did not return valid JSON',
            'output' => $output,
            'returnVar' => $returnVar
        ]);
    }

    return response()->json($json);
});

Route::middleware(['auth'])->group(function() {
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
    Route::get('/community', [CommunityPostController::class, 'index'])->name('community.index');
    Route::post('/community', [CommunityPostController::class, 'store'])->name('community.store');
    Route::post('/community/{post}/react', [CommunityPostController::class, 'react'])->name('community.react');
});

Route::get('/dashboard/update', [DashboardController::class, 'update']);

Route::get('/feeder', [FeederController::class, 'index'])->name('feeder.index');

Route::resource('community', CommunityPostController::class)->only([
    'store', 'update', 'destroy'
]);


require __DIR__.'/auth.php';
