<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BiometriaController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ViveiroController;
use App\Http\Controllers\UsoDiarioController;


Route::get('/', [SiteController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
Route::middleware('auth')->group(function () {
    

    Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
    Route::get('/produtos/cadastrar', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produtos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::resource('cultivos', CultivoController::class);
    Route::get('cultivos/{cultivo}/relatorio', [CultivoController::class, 'relatorio'])->name('cultivos.relatorio');
    Route::get('cultivos/{cultivo}/relatorio', [CultivoController::class, 'finalizar'])->name('cultivos.finalizar');


    
    Route::resource('uso_diario', UsoDiarioController::class);
    Route::resource('estoque', EstoqueController::class);
    Route::resource('biometrias', BiometriaController::class);

    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('viveiros', ViveiroController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Route::get('/biometrias', [BiometriaController::class, 'index'])->name('biometrias.index');
// Route::get('/biometrias/cadastrar', [BiometriaController::class, 'create'])->name('biometrias.create');
// Route::post('/biometrias', [BiometriaController::class, 'store'])->name('biometrias.store');
// Route::get('/biometrias/{biometria}/editar', [BiometriaController::class, 'edit'])->name('biometrias.edit');
// Route::get('/biometrias/{biometria}/detalhes', [BiometriaController::class, 'show'])->name('biometrias.show');
// Route::put('/biometrias/{biometria}', [BiometriaController::class, 'update'])->name('biometrias.update');
// Route::delete('/biometrias/{biometria}', [BiometriaController::class, 'destroy'])->name('biometrias.destroy');
 
 // Group routes that need admin role and authentication


require __DIR__.'/auth.php';

