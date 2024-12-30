<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Listar productos
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::orderBy('created_at', 'desc')->get();
            return response()->json($products, 200);
        }
    
        return view('products.index'); // Para solicitudes no Ajax
    }
    
    
    
    // Mostrar formulario de creación
    public function create()
    {
        return view('products.create');
    }

    // Guardar un producto
    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|unique:products',
        'name' => 'required',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'entry_date' => 'required|date',
        'expiry_date' => 'required|date|after:entry_date',
        'photo' => 'nullable|image|mimes:jpg,png|max:2048', // Verificar formato y tamaño
    ]);

    $photoPath = null;
    if ($request->hasFile('photo')) {
        // Guardar la foto en "storage/app/public/photos"
        $photoPath = $request->file('photo')->store('photos', 'public');
    }

    Product::create([
        'code' => $request->input('code'),
        'name' => $request->input('name'),
        'quantity' => $request->input('quantity'),
        'price' => $request->input('price'),
        'entry_date' => $request->input('entry_date'),
        'expiry_date' => $request->input('expiry_date'),
        'photo' => $photoPath, // Guardar la ruta relativa de la foto
    ]);

    return response()->json(['message' => 'Producto creado con éxito'], 201);
}


    // Mostrar formulario de edición
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }




    
    // Actualizar un producto
    public function update(Request $request, $id)
    {
        // Buscar el producto
        $product = Product::find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:entry_date',
            'photo' => 'nullable|image|max:2048', // Si se permite subir una imagen
        ]);
    
        // Subir y guardar la foto si se envió
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/photos');
            $validatedData['photo'] = basename($path);
        }
    
        // Actualizar el producto
        $product->update($validatedData);
    
        return response()->json(['message' => 'Producto actualizado exitosamente'], 200);
    }
    









    public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    $product->delete();

    return response()->json(['message' => 'Producto eliminado exitosamente'], 200);
}


public function show($id)
{
    // Buscar el producto por su ID
    $product = Product::find($id);

    if (!$product) {
        // Retornar error si el producto no existe
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    // Retornar los datos del producto en formato JSON
    return response()->json($product, 200);
}


}
