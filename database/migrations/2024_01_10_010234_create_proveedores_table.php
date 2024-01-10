<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //ejecutar la migracion
    public function up(): void
    {
        //create table proveedores...
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id(); //id autoincrementado y es la primery key
            $table->string('nombre',100);
            $table->text('direccion');
            $table->integer('telefono');
            $table->string('correo',50);
            $table->string('password',100);
            /**
             * created_at = cuando creaste el registro(hora/fecha)
             * updated_at = actualizes el registro
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * elimina la migracion
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
