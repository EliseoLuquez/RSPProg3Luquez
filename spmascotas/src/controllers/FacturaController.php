<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Models\Turno;
use App\Utils\ValidadorJWT;
use App\models\Factura;



class FacturaController {

    public function getAllFactura(Request $request, Response $response, $args)
    {
        $header = getallheaders();
        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);
        $usuario = Usuario::where('email', $usuario->email)->first();

        $facturas = Factura::where('id_usuario', $usuario->id)->get();

        $rta = json_encode($facturas);

        // $response->getBody()->write("Controller");
        $response->getBody()->write($rta);

        return $response;
    }

 
}