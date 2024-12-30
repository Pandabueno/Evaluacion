<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;

Route::post('/products/{id}', function (Request $request, $id) {
    if ($request->input('_method') === 'DELETE') {
        $product = App\Models\Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado exitosamente'], 200);
    }

    return response()->json(['message' => 'Método no permitido'], 405);
});


// Rutas del CRUD para productos
Route::resource('products', ProductController::class);

// Ruta principal (opcional)
Route::get('/', function () {
    return view('welcome');
});
