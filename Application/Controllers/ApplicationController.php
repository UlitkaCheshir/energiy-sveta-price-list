<?php
/**
 * Created by PhpStorm.
 * User: ULITKA
 * Date: 03.04.2019
 * Time: 10:27
 */

namespace Application\Controllers;

use Bramus\Router\Router;
use Application\Utils\MySQL;


class ApplicationController extends BaseController
{

    public function Start(){

        session_start([
            'cookie_lifetime' => 86400,
        ]);

//        MySQL::$db = new \PDO(
//                    "mysql:dbname=tipprada_trippradardb;host=127.0.0.1;charset=utf8",
//                    "tipprada_trippradarUser",
//                    "trippradarUser123456"
//                );

        $router = new Router();


        $routes = include_once '../Application/Models/PublicRoutes.php';

        $router->setNamespace('Application\\Controllers');

        foreach ($routes as $key => $path ){

            foreach ($path as $subKey => $value){

                $router->$key( $subKey , $value );

            }//foreach

        }//foreach

        $router->run();
        

    }//Start
}