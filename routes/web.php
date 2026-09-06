<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

foreach (['logo.svg', 'darkmode-logo.svg', 'icon-light.svg', 'icon-dark.svg', 'icon-monokrom.svg', 'logo-light.png', 'logo-dark.png', 'logo-light.webp', 'logo-dark.webp'] as $asset) {
    Route::get('/assets/'.$asset, fn () => response()->file(public_path('assets/'.$asset), [
        'Cache-Control' => 'public, max-age=86400',
    ]))->name('asset.'.str_replace(['.', ' '], '-', $asset));
}

Route::get('/favicon.ico', fn () => response()->file(public_path('favicon.ico'), [
    'Cache-Control' => 'public, max-age=86400',
]))->name('asset.favicon');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:login')->name('register.attempt');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['id', 'en'])) {
        abort(400);
    }
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return back();
})->name('lang.switch');

Route::get('/demo', function () {
    $user = User::where('email', \Database\Seeders\DemoAccountSeeder::DEMO_EMAIL)->first();

    if (! $user) {
        Artisan::call('db:seed', ['--class' => \Database\Seeders\DemoAccountSeeder::class, '--force' => true]);
        $user = User::where('email', \Database\Seeders\DemoAccountSeeder::DEMO_EMAIL)->first();
    }

    Auth::loginUsingId($user->id);
    session()->regenerate();

    return redirect('/');
})->name('demo.index');

Route::get('/', function () {
    if (auth()->check()) {
        return app(DashboardController::class)->index(request());
    }
    return view('landing');
})->name('dashboard');

Route::get('/seed-demo', function () {
    $token = env('DEMO_SEED_TOKEN');

    if (! $token || request('token') !== $token) {
        abort(403, 'Token tidak valid.');
    }

    Artisan::call('db:seed', ['--class' => 'AugDemoSeeder', '--force' => true]);

    return response(Artisan::output())->header('Content-Type', 'text/plain');
})->name('demo.seed');

Route::middleware(['auth', 'blockDemo'])->group(function () {
    Route::post('/reset-data', [DashboardController::class, 'resetData'])->name('reset-data');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->middleware('throttle:login')->name('profile.update');
    Route::get('/auth/google/sync', [AuthController::class, 'redirectToGoogle'])->middleware('auth')->name('google.sync');

    Route::get('/budget', [BudgetController::class, 'index'])->name('budget.index');
    Route::post('/budget/salary', [BudgetController::class, 'storeSalary'])->name('budget.salary.store');
    Route::post('/budget/allocate', [BudgetController::class, 'allocate'])->name('budget.allocate');
    Route::post('/budget/additional-income', [BudgetController::class, 'storeAdditionalIncome'])->name('budget.additional.store');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('/recurring', [RecurringExpenseController::class, 'index'])->name('recurring.index');
    Route::get('/recurring/create', [RecurringExpenseController::class, 'create'])->name('recurring.create');
    Route::post('/recurring', [RecurringExpenseController::class, 'store'])->name('recurring.store');
    Route::get('/recurring/{recurringExpense}/edit', [RecurringExpenseController::class, 'edit'])->name('recurring.edit');
    Route::patch('/recurring/{recurringExpense}', [RecurringExpenseController::class, 'update'])->name('recurring.update');
    Route::delete('/recurring/{recurringExpense}', [RecurringExpenseController::class, 'destroy'])->name('recurring.destroy');
    Route::post('/recurring/{recurringExpense}/pay', [RecurringExpenseController::class, 'markPaid'])->name('recurring.pay');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{format}', [ReportController::class, 'export'])->where('format', 'pdf|xlsx')->name('reports.export');
    Route::get('/reports/preview/{format}/file', [ReportController::class, 'preview'])->where('format', 'pdf')->name('reports.previewFile');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
