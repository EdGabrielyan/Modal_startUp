<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', function () {
        return view('admin');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::get('/{id}', [AdminController::class, 'show'])->name('show');
    Route::post('/', [AdminController::class, 'store'])->name('store');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('update');
    Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::delete('/{domains}', [AdminController::class, 'destroy'])->name('destroy');

});




Route::get('/widget.js', function (\Illuminate\Http\Request $request) {
    $key = $request->query('script');
    $js = <<<JAVASCRIPT
const key = "{$key}";

(async function () {
    if (!key) return;

    const currentPage = window.location.pathname;

    const response = await fetch("http://127.0.0.1:80/api/widget?script=" + key + "&page=" + encodeURIComponent(currentPage));
    const data = await response.json();

    if (data.error) return;

    setTimeout(() => {
        const modal = document.createElement('div');
        modal.innerHTML = `
<div style="position: fixed; top: 20%; left: 30%; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.3); z-index: 9999;">
    <h3>\${data.title}</h3>
    <p>\${data.message}</p>
    <button onclick="this.parentElement.remove()">Закрыть</button>
</div>`;
        document.body.appendChild(modal);
    },  data.delay || 3000);
})();
JAVASCRIPT;
    return response($js, 200)
        ->header('Content-Type', 'application/javascript')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Cross-Origin-Resource-Policy', 'cross-origin');
});





require __DIR__.'/auth.php';
