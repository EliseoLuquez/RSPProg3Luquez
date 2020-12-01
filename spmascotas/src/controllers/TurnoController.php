<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Turno;
use App\Models\Usuario;
use App\Models\Mascota;
use App\Models\Factura;
use App\Models\Tipo_Mascota;
use App\Utils\ValidadorJWT;
use DateTime;
use Symfony\Component\Console\Helper\Helper;

class TurnoController {

    public function getAllTurnos($request, $response, $args)
    {
        $rta = Turno::select('turnos.id', 'turnos.id_tipo', 'turnos.id_usuario', 'turnos.fecha', 'tipo_mascota.precio', 'usuarios.usuario', 'tipo_mascota.descripcion')
        ->join('usuarios', 'usuarios.id', 'turnos.id_usuario')
        ->join('tipo_mascota', 'tipo_mascota.id', 'turnos.id_tipo')
        ->get();



        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function addTurno($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $date = date('Y/m/d H:i',strtotime($body["fecha"]));
        //var_dump($date);
        $header = getallheaders();
        $token = $header['token'];
        $usuario = validadorJWT::ObtenerUsuario($token);
        $usuario = Usuario::where('email', $usuario->email)->first();
 

        $turno = new Turno;
        $turno->id_usuario = $usuario->id;
        $turno->id_tipo = $body["tipo_id"];
        $turno->fecha = $date;
        $turno->estado = 'En espera';
        $rta = json_encode(array("ok" => $turno->save()));      
    
        $response->getBody()->write($rta);

        return $response;
    }

    public function cambioEstado(Request $request, Response $response, $args)
    {
        //$body = $request->getParsedBody();
        //$header = getallheaders();
        $id = $args['idTurno'];

        $turno = Turno::where('id', $id)->first();

        //var_dump($turno->estado);
        if($turno != null)
        {
            if($turno->estado == 'En espera')
            {   
                $tipo = Tipo_Mascota::find($turno->id_tipo);
                $turno->estado = 'atendido';
                $factura = new Factura;
                $factura->id_turno = $turno->id;
                $factura->id_usuario = $turno->id_usuario;
                $factura->fecha = $turno->fecha;
                $factura->precio = $tipo->precio;
                $factura->save();
                

                $rta = json_encode($turno->save());
            }
            else
            {
                $rta = json_encode("El turno ya fue atendido");
            }
        }
        else
        {
            $rta = json_encode("La turno no existe");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}