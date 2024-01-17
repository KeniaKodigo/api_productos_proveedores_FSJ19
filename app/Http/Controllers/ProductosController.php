<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductosController extends Controller
{
    //metodo para verificar los productos del proveedor que se autentico
    public function index(Request $request){
        //asignar la autorizacion
        $token = $request->header('Authorization'); //jbdcksmlmfm

        //obtener los proveedores
        $proveedores = Proveedores::all(); //select * from proveedores

        foreach($proveedores as $proveedor){
            //Genera un token basic de autorizacion
            $credenciales = base64_encode($proveedor['correo']. ":" . $proveedor['password']);

            $token_esperado = "Basic " . $credenciales;
            if($token === $token_esperado){
                //proveedor existe en la bd

                //generar la consulta sql (obtener la informacion de los productos, nombre del proveedor en base al proveedor que entre por su ID)

                /**
                 * select productos.*, proveedores.nombre from productos inner join proveedores on productos.id_proveedor = proveedores.id where proveedores.id = ?
                 */

                 //join(4 argumento)
                 /**
                  * 1 -> nombre de la tabla
                  * 2-> primera parte de la conexion
                  * 3 -> =
                  */
                $productos = Productos::join('proveedores','productos.id_proveedor','=','proveedores.id')->select('productos.*','proveedores.nombre as proveedor')->where('proveedores.id','=',$proveedor['id'])->get();

                return response()->json([
                    "status" => 200,
                    "total_productos" => count($productos),
                    "detalle" => $productos
                ]);
            }
        }

        return response()->json([
            "status" => 400,
            "detalle" => "Credenciales INVALIDAS"
        ]);
    }

    //metodo para registrar producto
    public function store(Request $request){
        //asignar la autorizacion
        $token = $request->header('Authorization'); //jbdcksmlmfm

        //obtener los proveedores
        $proveedores = Proveedores::all(); //select * from proveedores
        foreach($proveedores as $proveedor){
            //Genera un token basic de autorizacion
            $credenciales = base64_encode($proveedor['correo']. ":" . $proveedor['password']);

            $token_esperado = "Basic " . $credenciales;
            if($token === $token_esperado){
                
                $datos = array(
                    "nombre" => $request->input("nombre"), //teclado gamer 
                    "descripcion" => $request->input("descripcion"),
                    "precio" => $request->input("precio"),
                    "marca" => $request->input("marca"),
                    "stock" => $request->input("stock"),
                );

                //validar si existe un archivo (imagen)
                if($request->hasFile('imagen')){ //true
                    //guardar la imagen
                    $imagen = $request->file('imagen'); //
                    //formateamos el nombre la imagen
                    //Str::slug("computadora-ASUS.svg")
                    $nombre_imagen = Str::slug($request->post('nombre')).".".$imagen->guessExtension();

                    //asignamos la ruta donde vamos a guardar la imagen (local)
                    $ruta = public_path("img/");  //public/img

                    //copiar la imagen a la ruta
                    copy($imagen->getRealPath(),$ruta.$nombre_imagen);
                    //public/img/computadora-ASUS.png
                }else{
                    $nombre_imagen = null;
                }

                //Guardando los datos a la bd -> save()
                $productos = new Productos();
                $productos->nombre = $datos['nombre'];
                $productos->descripcion = $datos['descripcion'];
                $productos->precio = $datos['precio'];
                $productos->marca = $datos['marca'];
                $productos->stock = $datos['stock'];
                $productos->imagen = $nombre_imagen;
                $productos->id_proveedor = $proveedor['id'];
                $productos->save(); //INSERT INTO ...

                return response()->json(
                    [
                        'status' => 200,
                        'detalle' => "Se ha registrado exitosamente"
                    ]
                );

            }
        }

        return response()->json([
            "status" => 400,
            "detalle" => "Credenciales INVALIDAS"
        ]);
    }

    //metodo para buscar por nombre de producto
    public function busquedaByNombre($busqueda){
        //SELECT productos.nombre, productos.descripcion, productos.precio, productos.imagen, proveedores.nombre FROM productos INNER JOIN proveedores ON productos.id_proveedor = proveedores.id WHERE productos.nombre LIKE '%$busqueda%';

        $productos = Productos::join('proveedores','productos.id_proveedor', '=', 'proveedores.id')->select('productos.nombre', 'productos.descripcion', 'productos.precio', 'productos.imagen', 'proveedores.nombre as proveedor')->where('productos.nombre','LIKE','%'.$busqueda .'%')->get();

        if(count($productos) > 0){
            return response()->json([
                "status" => 200,
                "total_productos" => count($productos),
                "detalle" => $productos
            ]);
        }

        return response()->json([
            "status" => 400,
            "detalle" => "No coincide ningun producto con la busqueda"
        ]);
    }

    
    public function precioProducto(){
        //mande todos los productos que tengan el precio mayor a $100 incluyendo el nombre y la direccion del proveedor

        //SELECT productos.*, proveedores.nombre, proveedores.direccion FROM productos INNER JOIN proveedores ON productos.id_proveedor = proveedores.id WHERE productos.precio > 100

        $productos = Productos::join('proveedores','productos.id_proveedor','=', 'proveedores.id')->select('productos.*', 'proveedores.nombre as proveedor', 'proveedores.direccion')->where('productos.precio', '>', 100)->get();

        return response()->json(
            [
                "status" => 200,
                "detalle" => $productos
            ]
        );
    }

}
