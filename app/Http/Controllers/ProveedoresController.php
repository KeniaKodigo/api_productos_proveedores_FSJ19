<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    //metodo para obtener todos los proveedores
    public function index(){
        //select * from proveedores -> json
        //consulta mapeada

        $proveedores = Proveedores::all(); //all() //select * from table
        //select nombre from proveedores where id = 5
        //Proveedores::select('nombre')->get();
        //Proveedores::select('nombre')->where('id','=',5)->get();
        
        //Eloquent ORM / queries builder
        //la informacion la mando en un json
        if(count($proveedores) == 0){
            return  response()->json("No hay rgistros de proveedores");
        }
        return  response()->json($proveedores);
    }
}
