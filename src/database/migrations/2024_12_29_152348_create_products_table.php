<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de producto
            $table->string('name');           // Nombre del producto
            $table->integer('quantity');      // Cantidad
            $table->string('photo')->nullable(); // Fotografía
            $table->decimal('price', 8, 2);   // Precio
            $table->date('entry_date');       // Fecha de ingreso
            $table->date('expiry_date');      // Fecha de vencimiento
            $table->timestamps();             // Fechas de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
