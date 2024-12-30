@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<style>
    /* Estilo general */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
        color: #343a40;
    }

    h1 {
        color: #ff6f00;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    .btn-primary {
        background-color: #ff6f00;
        border-color: #ff6f00;
    }

    .btn-primary:hover {
        background-color: #e65c00;
        border-color: #e65c00;
    }

    .btn-warning {
        background-color: #ffcc00;
        border-color: #ffcc00;
        color: #212529;
    }

    .btn-warning:hover {
        background-color: #e6b800;
        border-color: #e6b800;
    }

    .btn-danger {
        background-color: #ff4d4d;
        border-color: #ff4d4d;
    }

    .btn-danger:hover {
        background-color: #e63939;
        border-color: #e63939;
    }

    .table {
        border: 1px solid #ddd;
        background-color: #ffffff;
    }

    .table th {
        background-color: #ff6f00;
        color: white;
    }

    .table th:hover {
        cursor: pointer;
        background-color: #e65c00;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffe6cc;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background-color: #fff7e6;
    }

    .modal-header {
        background-color: #ff6f00;
        color: white;
    }

    .btn-close {
        background-color: white;
        border-radius: 50%;
    }

    .btn-close:hover {
        background-color: #ddd;
    }

    /* Input focus styles */
    input:focus {
        border-color: #ff6f00;
        box-shadow: 0 0 5px rgba(255, 111, 0, 0.5);
    }
</style>

<div class="container mt-4">
    <!-- Metaetiqueta para CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1>Catálogo de Productos</h1>
    <button class="btn btn-primary mb-3" id="btn-add-product" data-bs-toggle="modal" data-bs-target="#productModal">
        Agregar Producto
    </button>

    <input type="text" id="search" class="form-control" placeholder="Buscar productos...">

    <!-- Tabla de productos -->
    <table class="table table-striped table-bordered text-center" id="products-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha de Ingreso</th>
                <th id="expiry-date-header" style="cursor: pointer;">Fecha de Vencimiento</th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se cargarán los productos dinámicamente mediante JavaScript -->
        </tbody>
    </table>
</div>




<!-- Modal para agregar/editar productos -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Formulario -->
            <form id="product-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product-code" class="form-label">Código</label>
                        <input type="text" class="form-control" id="product-code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="product-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-quantity" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="product-quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="product-price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-entry-date" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" id="product-entry-date" name="entry_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-expiry-date" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="product-expiry-date" name="expiry_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-photo" class="form-label">Fotografía</label>
                        <input type="file" class="form-control" id="product-photo" name="photo" accept=".jpg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
