<?php

namespace Config;

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuarioController;
use App\Controllers\Tipo_MascotaController;
use App\Controllers\MascotaController;
use App\Controllers\TurnoController;
use App\Middlewares\UsuarioValidateMiddleware;
use App\Middlewares\RegistroMiddleware;
use App\Middlewares\ExisteUsuarioMiddleware;
use App\Middlewares\LoginMiddleware;
use App\Middlewares\TurnoMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\ClienteMiddleware;
use App\Controllers\FacturaController;

return function ($app){
        //PUNTO 1 
        $app->post('/users', UsuarioController::class . ':add')->add(ExisteUsuarioMiddleware::class)->add(RegistroMiddleware::class);
    
        //PUNTO 2 
        $app->post('/login', UsuarioController::class . ':login')->add(LoginMiddleware::class);
        
        $app->post('/tipo_mascota', Tipo_MascotaController::class . ':addTipo_mascota')->add(UsuarioValidateMiddleware::class);
       
        //$app->get('/usuario/{email}', UsuarioController::class . ':getUsuario');
        //PUINTO 3
        $app->post('/mascota', MascotaController::class . ':addMascota')->add(UsuarioValidateMiddleware::class)->add(AdminMiddleware::class);
    
        //PUNTO 4
        $app->group('/turno', function (RouteCollectorProxy $group) {
             $group->post('', TurnoController::class . ':addTurno')->add(ClienteMiddleware::class);
             //PUNTO 5
             $group->put('/{idTurno}', TurnoController::class . ':cambioEstado')->add(AdminMiddleware::class);
        })->add(UsuarioValidateMiddleware::class);
        
        $app->get('/turnos', TurnoController::class . ':getAllTurnos')->add(UsuarioValidateMiddleware::class)->add(AdminMiddleware::class);
        
        //$app->post('/turno', TurnoController::class . ':addTurno')->add(UsuarioValidateMiddleware::class);
    
        //PUNTO 7 ->add(ExisteServicioMiddleware::class)
        $app->get('/factura', FacturaController::class . ':getAllFactura')->add(UsuarioValidateMiddleware::class)->add(ClienteMiddleware::class);
        

};