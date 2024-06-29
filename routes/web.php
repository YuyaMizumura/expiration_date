<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// dashboard
use App\Http\Controllers\Dashboard\
{
    Expense\IndexController as ExpenseController,
    Expiration\IndexController as ExpirationController,
    Expiration\CompleteController as ExpirationCompleteController,
};

// category
use App\Http\Controllers\Category\
{
    IndexController as CategoryController,
    MakeController as CatMakeController,
    PostCreateController as CatPostCreateController,
    DeleteController as CatDeleteController,
    PostEditController as CatPostEditController,
};

// templatex
use App\Http\Controllers\Template\
{
    IndexController as TemplateController,
    MakeController as TempMakeController,
    PostCreateController as TempPostCreateController,
    PostEditController as TempPostEditController,
    DeleteController as TempDeleteController,
};

// sign up
use App\Http\Controllers\SignUp\
{
    IndexController as SignUpController,
    MakeController as SignupMakeController,
    PostCreateController as SignupPostCreateController,
    PostEditController as SignupPostEditController,
};

// graph
use App\Http\Controllers\Graph\
{
    Expense\IndexController as GraphExpenseController,
    Expiration\IndexController as GraphExpirationController,  
};

// // share
// use App\Http\Controllers\Share\
// {
//     IndexController as ShareController,
//     MakeController as ShareMakeController,
//     PostAppliController as SharePostAppliController,
//     GetJudgeController as ShereGetJudgeController,
//     GetCancellController as ShereGetCancellController,
// };

Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware('auth')->group(function()
{
    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // dashboard
    Route::prefix('dashboard')->group(function() {
        Route::get('/', [ExpenseController::class, '__invoke'])->name('dashboard');

        Route::get('/expiration', [ExpirationController::class, '__invoke'])->name('dashboard.expiration');
        Route::get('/complete', [ExpirationCompleteController::class, '__invoke'])->name('dashboard.complete');
    });

    // category
    Route::prefix('category')->group(function() {
        Route::get('/', [CategoryController::class, '__invoke'])->name('category');
        Route::get('/make', [CatMakeController::class, '__invoke'])->name('category.make');

        Route::get('/edit', [CatMakeController::class, '__invoke'])->name('category.edit');
        Route::get('/delete/{id}', [CatDeleteController::class, '__invoke'])->name('category.delete');

        Route::post('/create', [CatPostCreateController::class, '__invoke'])->name('category.post.create');
        Route::post('/edit', [CatPostEditController::class, '__invoke'])->name('category.post.edit');
    });

    // template
    Route::prefix('template')->group(function() {
        Route::get('/', [TemplateController::class, '__invoke'])->name('template');
        Route::get('/make', [TempMakeController::class, '__invoke'])->name('template.make');
    
        Route::post('/create', [TempPostCreateController::class, '__invoke'])->name('template.post.create');
        Route::post('/edit', [TempPostEditController::class, '__invoke'])->name('template.post.edit');
        Route::get('/delete', [TempDeleteController::class, '__invoke'])->name('template.delete');
    });

    // signup
    Route::prefix('signup')->group(function() {
        Route::get('/', [SignUpController::class, '__invoke'])->name('signup');
        Route::get('/make',[SignupMakeController::class, '__invoke'])->name('signup.make');
    
        Route::post('/create', [SignupPostCreateController::class, '__invoke'])->name('signup.post.create');
        Route::post('/edit', [SignupPostEditController::class, '__invoke'])->name('signup.post.edit');
        Route::post('/manuCreate', [TempPostCreateController::class, '__invoke'])->name('template.post.manuCreate');
    });

    // graph
    Route::prefix('graph')->group(function() {
        Route::get('/graph/expense',[GraphExpenseController::class, '__invoke'])->name('graph.expense');
        Route::get('/graph/expiration',[GraphExpirationController::class, '__invoke'])->name('graph.expiration');
    });

    // share
    // Route::prefix('share')->group(function() {
    //     Route::get('/',[ShareController::class, '__invoke'])->name('share');
    //     Route::get('/make',[ShareMakeController::class, '__invoke'])->name('share.make');
    //     Route::get('/judge',[ShereGetJudgeController::class, '__invoke'])->name('share.judge');
    //     Route::get('/cancell',[ShereGetCancellController::class, '__invoke'])->name('share.cancell');
    //     Route::post('/appli',[SharePostAppliController::class, '__invoke'])->name('share.post.appli');
    // });
});

require __DIR__.'/auth.php';
