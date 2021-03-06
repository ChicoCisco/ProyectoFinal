<?php

namespace App\Http\Controllers;

use App\Models\registro_entradas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class registro_entradasController extends Controller
{
    public function ChecarExistencia()
    {
        $response = Http::get('https://io.adafruit.com/api/v2/CarlosLpz/feeds/codigosnfc/data?limit=1&X-AIO-Key=aio_niyI86YjyLiULi30kdQKOOKtjSWo');
        $posts=json_decode($response->body());

        $postuser1 = array();
        
        

        foreach($posts as $post)
        {
            $t=$post->value;
            //$llave = User::find($t);

            //if($post->value==$llave)
            //{
            //    $respuesta="1";
            //}

            if (User::where('LlaveIngreso', $post->value)->exists()) {

                $respuesta='1';

                $response = Http::withHeaders([
                    'X-AIO-Key' => 'aio_niyI86YjyLiULi30kdQKOOKtjSWo'
                ])->post('https://io.adafruit.com//api/v2/CarlosLpz/feeds/nfcactivado/data', [
                    'value' => "1",
                ]);
            }

            //else
            //{
            //    $respuesta='-';
            //}
        }

        /*echo json_encode($postuser1);*/
        //return $respuesta;
    }

    public function ChecarManual(Request $request)
    {
        //$response = Http::get('https://io.adafruit.com/api/v2/CarlosLpz/feeds/codigosnfc/data?limit=1&X-AIO-Key=aio_niyI86YjyLiULi30kdQKOOKtjSWo');
        //$posts=json_decode($response->body());

        //$postuser1 = array();
        
        

        //foreach($posts as $post)
        //{
        //    $t=$post->value;
            //$llave = User::find($t);

            //if($post->value==$llave)
            //{
            //    $respuesta="1";
            //}

            if (User::where('LlaveIngreso', $request->LlaveIngreso)->exists()) {

                //$respuesta='1';

                $response = Http::withHeaders([
                    'X-AIO-Key' => 'aio_niyI86YjyLiULi30kdQKOOKtjSWo'
                ])->post('https://io.adafruit.com//api/v2/CarlosLpz/feeds/nfcactivado/data', [
                    'value' => "1",
                ]);

                $responde= "Usuario Verificado Exitosamente";
            }

            else
            {
                $responde = "Usuario NO puede Ingresar, Saludos";
            }

            //else
            //{
            //    $respuesta='-';
            //}
        //}

        /*echo json_encode($postuser1);*/
        return $response;
    }

    public function ConsultarEntradas()
    {
        $insertar = new registro_entradas();

        $response = Http::get('https://io.adafruit.com/api/v2/CarlosLpz/feeds/codigosnfc/data?limit=10&X-AIO-Key=aio_niyI86YjyLiULi30kdQKOOKtjSWo');
        $posts=json_decode($response->body());

        $postuser1 = array();

        foreach($posts as $post)
        {
            if($post->value)
            {
                $postuser1[]=$post->value;
                $insertar->value=$post->value;
            }

            if($post->created_at)
            {
                $postuser1[]=$post->created_at;
                $insertar->F_ingreso=$post->created_at;
            }
            
        }

        $insertar->save();
        /*echo json_encode($postuser1);*/
        return $postuser1;
        //return $postuser2;
        
    }

    public function ConsultarUsuarios()
    {
        $users=User::table('users')->get();

        return view('user.index', ['name' => $users]);
    }
}
