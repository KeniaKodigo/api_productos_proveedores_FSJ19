<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

//require / include

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
            return  response()->json("No hay registros de proveedores");
        }
        return  response()->json($proveedores);
    }

    //metodo para registrar proveedor
    public function store(Request $request){

        //arreglo de objetos (llave-valor) /name
        $datos = array(
            "nombre" => $request->input('nombre'),
            "direccion" => $request->input('direccion'),
            "telefono" => $request->input('telefono'),
            "correo" => $request->input('correo'),
            "password" => $request->input('password'),
        );

        if(!empty($datos)){
            //insert into table () VALUES () => save()
            $proveedor = new Proveedores();
            $proveedor->nombre = $datos['nombre'];
            $proveedor->direccion = $datos['direccion'];
            $proveedor->telefono = $datos['telefono'];
            $proveedor->correo = $datos['correo'];
            //encriptando la password del usuario
            $password_encriptada = Hash::make($datos['password']);
            $proveedor->password = $password_encriptada;
            $proveedor->save();

            return  response()->json([
                "status" => 200,
                "detalle" => "Proveedor registrado exitosamente",
                "credenciales" => array(
                    "correo" => $datos['correo'],
                    "llave_secreta" => $password_encriptada
                )
            ]);
        }else{
            return  response()->json("Necesitas llenar todos los campos");
        }
    }

    //metodo para obtener proveedor por su id
    public function getById($id){
        //select * from table where id = $id
        //find solo encuentra registros en base al id
        $proveedor = Proveedores::find($id);
        //select * from proveedores where correo = "kenia@gmail.com";
        //Proveedores::select('*')->where('correo','=','kenia@gmail.com')->get();
        if(empty($proveedor)){
            return response()->json([
                "status" => 400,
                "detalle" => "ID no encontrado"
            ]);
        }

        return response()->json([
            "status" => 200,
            "detalle" => $proveedor
        ]);
    }

    //metodo para actualizar los datos
    public function update(Request $request, $id){
        //arreglo de objetos (llave-valor) /name
        $datos = array(
            "nombre" => $request->input('nombre'),
            "direccion" => $request->input('direccion'),
            "telefono" => $request->input('telefono'),
            "correo" => $request->input('correo'),
        );

        if(!empty($datos)){
            //UPDATE table SET campo = ? WHERE id = ?
            $proveedor = Proveedores::find($id); //kenia
            if(empty($proveedor)){
                return response()->json([
                    "status" => 400,
                    "detalle" => "Proveedor no existe"
                ]);
            }
            $proveedor->nombre = $datos['nombre'];
            $proveedor->direccion = $datos['direccion'];
            $proveedor->telefono = $datos['telefono'];
            $proveedor->correo = $datos['correo'];
            $proveedor->update();

            return  response()->json([
                "status" => 200,
                "detalle" => "El proveedor se ha actualizado correctamente"
            ]);
        }else{
            return  response()->json("Necesitas llenar todos los campos");
        }
    }

    //metodo para eliminar un proveedor
    public function destroy($id){
        //delete()
        $proveedor = Proveedores::find($id); //kenia
        if(empty($proveedor)){
            return response()->json([
                "status" => 400,
                "detalle" => "Proveedor no existe"
            ]);
        }
        $proveedor->delete();
        return response()->json([
            "status" => 200,
            "detalle" => "Proveedor eliminado correctamente"
        ]);

        //Proveedores::where('id','=',$id)->delete(); (otra forma de eliminar)
    }

    //metodo para logear al proveedor y generar el token
    public function login(Request $request){
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        //generar una consulta sql (obtener el proveedor donde el correo sea igual ? y password igual ? )
        //select * from proveedores where correo = ? AND password = ?

        $proveedor = Proveedores::where('correo','=', $request->correo)->where('password','=',$request->password)->first(); //true / false

        if ($proveedor) {
            //existe el proveedor y voy a generar el token

            /*$token = $user->createToken('token-name')->plainTextToken;
            return response()->json(['token' => $token], 200);*/
        } else {
            return response()->json(['message' => 'Credenciales Invalidas'], 401);
        }
    }
}
