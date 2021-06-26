<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    // return view('home');
    return redirect()->route('books.index');
});

Route::post('/livres/importer', [BookController::class, 'import'])->name('books.import');
Route::get('/livres', [BookController::class, 'index'])->name('books.index');
Route::post('/livres', [BookController::class, 'store'])->name('books.store');
Route::get('/livres/ajouter', [BookController::class, 'create'])->name('books.create');
Route::get('/livres/{id}', [BookController::class, 'show'])->name('books.show');
Route::put('/livres/{id}', [BookController::class, 'update'])->name('books.update');
Route::get('/livres/{id}/modifier', [BookController::class, 'edit'])->name('books.edit');
Route::delete('/livres/{id}', [BookController::class, 'destroy'])->name('books.delete');