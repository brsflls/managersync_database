<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;

use App\Http\Controllers\ClienteController;

use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReporteGeneralController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FacturaCompraController;
use App\Http\Controllers\FacturaProductoController;
use App\Http\Controllers\FacturaReferenciaController;


// Rutas para Facturas
Route::post('/facturas', [FacturaController::class, 'store']); // Crear factura
Route::get('/facturas', [FacturaController::class, 'index']);  // Obtener todas las facturas
Route::get('/facturas/{id}', [FacturaController::class, 'show']);  // Obtener una factura por ID
Route::put('/facturas/{id}', [FacturaController::class, 'update']);  // Actualizar factura por ID
Route::delete('/facturas/{id}', [FacturaController::class, 'destroy']);  // Eliminar factura por ID

// Rutas para Detalle de Facturas
Route::post('/detalles-factura', [DetalleFacturaController::class, 'store']);  // Crear detalle de factura
Route::get('/detalles-factura/{id}', [DetalleFacturaController::class, 'show']);  // Obtener detalles de una factura
Route::put('/detalles-factura/{id}', [DetalleFacturaController::class, 'update']);  // Actualizar un detalle de factura
Route::delete('/detalles-factura/{id}', [DetalleFacturaController::class, 'destroy']);  // Eliminar un detalle de factura
Route::put('productos/{id}/reducir-stock', [ProductoController::class, 'reducirStock']);





Route::get('/usuarios/all', [UsuarioController::class, 'index']);


Route::get('/detalles/all', [DetalleFacturaController::class, 'index']);
Route::get('/reportes/all', [ReporteGeneralController::class, 'index']);
Route::get('/productos/all', [ProductoController::class, 'index']);
Route::get('/facturas/all', [FacturaController::class, 'index']);


// Rutas de autenticación

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::middleware('auth:sanctum')->post('/update-Profile', [AuthController::class, 'updateProfile']);





// Ruta para eliminar la cuenta

Route::middleware('auth:sanctum')->delete('/delete-account', [AuthController::class, 'deleteAccount']);



// Rutas de usuario (requiere autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UsuarioController::class, 'show']);
   
});
Route::middleware('auth:sanctum')->get('/usuario', [UsuarioController::class, 'show']);

// Productos
Route::get('/productos/all', [ProductoController::class, 'index']);
Route::post('/productos', [ProductoController::class, 'store'])->name('producto.store');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('producto.show');
Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('producto.edit');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('producto.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('producto.destroy');

Route::get('/proveedores/all', [ProveedorController::class, 'index']);
Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
Route::get('/proveedores/{id}', [ProveedorController::class, 'show'])->name('proveedores.show');
Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->name('proveedores.update');
Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');

//Rutas Compras 
// Rutas para Compras
Route::get('/compras/all', [FacturaCompraController::class, 'index']);
Route::post('/compras', [FacturaCompraController::class, 'store']);
Route::get('/compras/{id}', [FacturaCompraController::class, 'show']);
Route::put('/compras/{id}', [FacturaCompraController::class, 'update']);
Route::delete('/compras/{id}', [FacturaCompraController::class, 'destroy']);

// Rutas para Productos
Route::get('/productos-servicios/all', [FacturaProductoController::class, 'index']);
Route::post('/productos-servicios', [FacturaProductoController::class, 'store']);
Route::get('/productos-servicios/{id}', [FacturaProductoController::class, 'show']);
Route::put('/productos-servicios/{id}', [FacturaProductoController::class, 'update']);
Route::delete('/productos-servicios/{id}', [FacturaProductoController::class, 'destroy']);

// Rutas para Referencias
Route::get('/referencias/all', [FacturaReferenciaController::class, 'index']);
Route::post('/referencias', [FacturaReferenciaController::class, 'store']);
Route::get('/referencias/{id}', [FacturaReferenciaController::class, 'show']);
Route::put('/referencias/{id}', [FacturaReferenciaController::class, 'update']);
Route::delete('/referencias/{id}', [FacturaReferenciaController::class, 'destroy']);

// Clientes

Route::get('/clientes/all', [ClienteController::class, 'index']);
Route::post('/clientes', [ClienteController::class, 'store'])->name('cliente.store');
Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('cliente.show');
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('cliente.update');
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('cliente.destroy');
