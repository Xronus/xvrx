<?php

use App\Http\Controllers\Admin\AdminAccountParserController;
use App\Http\Controllers\Admin\AdminClassController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminHowToStartController;
use App\Http\Controllers\Admin\AdminLogoController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminRaceController;
use App\Http\Controllers\Admin\AdminRealmController;
use App\Http\Controllers\Admin\AdminShopCategoryController;
use App\Http\Controllers\Admin\AdminShopController;
use App\Http\Controllers\Admin\AdminSocialController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVoteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LadderController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['ru', 'en', 'de', 'es', 'fr'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/cp', [LoginController::class, 'showLoginForm'])->name('login')->middleware('throttle:6,1');
    Route::post('/cp', [LoginController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/cp/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request')->middleware('throttle:6,1');
    Route::post('/cp/forgot-password', [LoginController::class, 'sendPasswordResetLink'])->name('password.email')->middleware('throttle:10,1');
    Route::get('/cp/reset-password', [LoginController::class, 'showResetForm'])->name('password.reset')->middleware('throttle:6,1');
    Route::post('/cp/reset-password', [LoginController::class, 'reset'])->name('password.update')->middleware('throttle:6,1');
    Route::get('/cp/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('throttle:6,1');
    Route::post('/cp/register', [RegisterController::class, 'register'])->middleware('throttle:6,1');
});

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::post('/cp/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/cp/cabinet', [CabinetController::class, 'index'])->name('cabinet');
    Route::get('/cp/characters', [CabinetController::class, 'characters'])->name('cabinet.characters');
    Route::get('/cp/votes', [VoteController::class, 'index'])->name('cabinet.votes');
    Route::post('/cp/votes/{voteTop}/claim', [VoteController::class, 'claim'])->name('cabinet.votes.claim')->middleware('throttle:6,1');
    Route::get('/cp/shop', [ShopController::class, 'index'])->name('shop');
    Route::post('/cp/shop/buy', [ShopController::class, 'buy'])->name('shop.buy')->middleware('throttle:10,1');
});

Route::middleware(['auth', 'check.banned', 'admin'])->prefix('powerpuffsiteadmin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/languages', [AdminController::class, 'languages'])->name('languages.index');
    Route::post('/languages/toggle', [AdminController::class, 'toggleLanguage'])->name('languages.toggle');
    Route::get('/mail', [AdminController::class, 'mail'])->name('mail.index');
    Route::put('/mail', [AdminController::class, 'updateMail'])->name('mail.update');

    Route::get('/account-parser', [AdminAccountParserController::class, 'index'])->name('account-parser.index');
    Route::post('/account-parser/parse', [AdminAccountParserController::class, 'parse'])->name('account-parser.parse');

    Route::get('/logo', [AdminLogoController::class, 'index'])->name('logo.index');
    Route::post('/logo/upload', [AdminLogoController::class, 'upload'])->name('logo.upload');
    Route::post('/logo/set-current', [AdminLogoController::class, 'setCurrent'])->name('logo.set-current');
    Route::post('/logo/delete', [AdminLogoController::class, 'delete'])->name('logo.delete');

    Route::get('/howtostart', [AdminHowToStartController::class, 'index'])->name('howtostart.index');
    Route::put('/howtostart', [AdminHowToStartController::class, 'update'])->name('howtostart.update');

    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/realms', [AdminRealmController::class, 'index'])->name('realms.index');
    Route::get('/realms/create', [AdminRealmController::class, 'create'])->name('realms.create');
    Route::post('/realms', [AdminRealmController::class, 'store'])->name('realms.store');
    Route::get('/realms/{realm}/edit', [AdminRealmController::class, 'edit'])->name('realms.edit');
    Route::put('/realms/{realm}', [AdminRealmController::class, 'update'])->name('realms.update');
    Route::delete('/realms/{realm}', [AdminRealmController::class, 'destroy'])->name('realms.destroy');

    Route::get('/socials', [AdminSocialController::class, 'index'])->name('socials.index');
    Route::get('/socials/create', [AdminSocialController::class, 'create'])->name('socials.create');
    Route::post('/socials', [AdminSocialController::class, 'store'])->name('socials.store');
    Route::get('/socials/{social}/edit', [AdminSocialController::class, 'edit'])->name('socials.edit');
    Route::put('/socials/{social}', [AdminSocialController::class, 'update'])->name('socials.update');
    Route::post('/socials/{social}/toggle', [AdminSocialController::class, 'toggle'])->name('socials.toggle');
    Route::delete('/socials/{social}', [AdminSocialController::class, 'destroy'])->name('socials.destroy');

    Route::get('/votes', [AdminVoteController::class, 'index'])->name('votes.index');
    Route::put('/votes', [AdminVoteController::class, 'update'])->name('votes.update');

    Route::get('/races', [AdminRaceController::class, 'index'])->name('races.index');
    Route::get('/races/create', [AdminRaceController::class, 'create'])->name('races.create');
    Route::post('/races', [AdminRaceController::class, 'store'])->name('races.store');
    Route::get('/races/{race}/edit', [AdminRaceController::class, 'edit'])->name('races.edit');
    Route::put('/races/{race}', [AdminRaceController::class, 'update'])->name('races.update');
    Route::delete('/races/{race}', [AdminRaceController::class, 'destroy'])->name('races.destroy');

    Route::get('/classes', [AdminClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [AdminClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [AdminClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}/edit', [AdminClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{class}', [AdminClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}', [AdminClassController::class, 'destroy'])->name('classes.destroy');

    Route::get('/features', [AdminFeatureController::class, 'index'])->name('features.index');
    Route::get('/features/create', [AdminFeatureController::class, 'create'])->name('features.create');
    Route::post('/features', [AdminFeatureController::class, 'store'])->name('features.store');
    Route::get('/features/{feature}/edit', [AdminFeatureController::class, 'edit'])->name('features.edit');
    Route::put('/features/{feature}', [AdminFeatureController::class, 'update'])->name('features.update');
    Route::delete('/features/{feature}', [AdminFeatureController::class, 'destroy'])->name('features.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminUserController::class, 'unban'])->name('users.unban');

    Route::post('/shop/{shop}/toggle', [AdminShopController::class, 'toggle'])->name('shop.toggle');
    Route::resource('shop', AdminShopController::class)->except(['show']);
    Route::resource('shop-categories', AdminShopCategoryController::class)->except(['show']);
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::get('/ladder', [LadderController::class, 'index'])->name('ladder');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');
Route::get('/policy', function () {
    return view('policy');
})->name('policy');
