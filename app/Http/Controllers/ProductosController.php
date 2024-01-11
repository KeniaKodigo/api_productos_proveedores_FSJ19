<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    //metodo para verificar los productos del proveedor que se autentico
    public function index(Request $request){
        //asignar la autorizacion
        $token = $request->header('Authorization'); //jbdcksmlmfm

        //obtener los proveedores
        $proveedores = Proveedores::all();

        foreach($proveedores as $proveedor){
            //Genera un token basic de autorizacion
            $credenciales = base64_encode($proveedor['correo']. ":" . $proveedor['password']);

            $token_esperado = "Basic " . $credenciales;
            if($token === $token_esperado){
                //proveedor existe

                return response()->json([
                    "status" => 200,
                    "detalle" => "Credenciales validas, vea sus productos"
                ]);
            }
        }

        return response()->json([
            "status" => 400,
            "detalle" => "Credenciales INVALIDAS"
        ]);
    }
}
