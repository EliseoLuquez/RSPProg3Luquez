<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Tipo_Mascota;
use App\Models\Turno;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use Illuminate\Contracts\Validation\Validator;

class Tipo_MascotaController {


    public function addTipo_mascota(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);

        if($usuario->tipo == 'admin')
        {
            $tipoMascota = new Tipo_Mascota();
            $tipoMascota->descripcion = $body["descripcion"];
            $tipoMascota->precio = $body["precio"];
            $rta = json_encode(array("ok" => $tipoMascota->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar Tipo Mascota");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}