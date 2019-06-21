<?php
/**
 * Created by PhpStorm.
 * User: ULITKA
 * Date: 03.04.2019
 * Time: 10:24
 */

namespace Application\Controllers;

use Application\Service\UserService;
use Application\Utils\Request;
use Application\Utils\Storage;

require_once '../vendor/autoload.php';

abstract class BaseController
{

    protected $request;
    protected $storage;

    protected $google_client_id ;
    protected $google_client_secret ;
    protected $google_api_key;

    protected $redirect_url;
    protected $currentUser;

    protected $clientGoogle;

    public function __construct(){

        $userService = new UserService();

        $this->currentUser = $userService->getCurrentUser();

        $this->request = new Request();
        $this->storage = new Storage();


    }//__construct

    /**
     * @return mixed
     */
    protected function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param mixed $storage
     */
    protected  function setStorage($storage){
        $this->storage = $storage;
    }//setStorage

    protected function createUrl( $controller , $action ){

        return "?ctrl=$controller&act=$action";

    }

    protected function json( $code , $data ){

        http_response_code($code);
        header('Content-type: application/json');
        echo json_encode($data); //  res.send();
        exit();

    }//json

}//BaseController