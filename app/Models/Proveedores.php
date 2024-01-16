<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Proveedores extends Model
{
    //HasFactory: generar datos de prueba
    //HasApiTokens: es para generar un token para la autenticacion de los proveedores
    use HasFactory, Authenticatable, HasApiTokens;
    //asignamos el nombre de la tabla de la bd
    protected $table = "proveedores";
}
