<?php
/**
 * Created by PhpStorm.
 * User: ULITKA
 * Date: 04.04.2019
 * Time: 13:28
 */

namespace Application\Service;

use Application\Controllers\Constants;
use Application\Utils\MySQL;
use Bcrypt\Bcrypt;


class UserService
{

    public function AddUser($userEmail, $userName, $userPassword, $userHash, $userType){

        $const = new Constants();

        if($userType !== $const->typeUserRegister){ //если пользователь с гугла или фейсбук

            $user = R::dispense( 'users' );
            $user->useremail = $userEmail;
            $user->username = $userName;

            if(!$this->GetUser($userEmail)){
                $id = R::store( $user );
                return $id;
            }//if

            return null;
        }//if
        else{ // если пользователь просто регистрируеться

            $isUser = MySQL::$db->prepare("SELECT userEmail FROM users WHERE userEmail = :userEmail");
            $isUser->bindParam(':userEmail', $userEmail,\PDO::PARAM_STR);
            $isUser->execute();

            $result = $isUser->fetchAll(\PDO::FETCH_OBJ);

            if(!$result){

                $bcrypt = new Bcrypt();
                $bcrypt_version = '2y';
                $heshPassword = $bcrypt->encrypt($userPassword,$bcrypt_version);

                $stm = MySQL::$db->prepare("INSERT INTO users (userEmail, userName, userPassword, userHash, verification, status_id, type_id)
                                            VALUES(  :email , :name , :password , :hash, false, :status, :type )");

                $stm->bindParam(':name' , $userName , \PDO::PARAM_STR);
                $stm->bindParam(':email' , $userEmail , \PDO::PARAM_STR);
                $stm->bindParam(':hash' , $userHash , \PDO::PARAM_STR);
                $stm->bindParam(':password' , $heshPassword , \PDO::PARAM_STR);
                $stm->bindParam(':status' , $const->statusNOVIP , \PDO::PARAM_INT);
                $stm->bindParam(':type' , $userType , \PDO::PARAM_INT);
                $stm->execute();

                return  MySQL::$db->lastInsertId();
            }//if

            return null;

        }//else

    }//AddUser

    public function GetUser($parametr, $userLogin){

//        $user = R::findOne( 'users', ' useremail = ? OR id = ? OR userlogin = ? ', [
//
//            [ $parametr, \PDO::PARAM_STR ],
//            [ $parametr, \PDO::PARAM_INT],
//            [ $userLogin, \PDO::PARAM_STR ],
//        ] );
//
//        return $user;

    }//GetUser

    public function VerificationUsers($token){

        $stm = MySQL::$db->prepare("UPDATE users SET verification = true, userHash = NULL WHERE userHash =:token");
        $stm -> bindParam(':token', $token, \PDO::PARAM_STR);
        $result = $stm -> execute();

        return $result;

    }//VerificationUser

    public function AuthoriseUsers($userEmail, $userPass){

        $bcrypt = new Bcrypt();

        $stm = MySQL::$db->prepare("SELECT id, isAdmin, userEmail, userPassword, verification, userName
                                    FROM users WHERE userEmail =:userEmail");

        $stm->bindParam(':userEmail', $userEmail, \PDO::PARAM_STR);
        $stm->execute();

        $user = $stm->fetch(\PDO::FETCH_OBJ);

        if(!$user){

            return [
                'code'=>401,
                'message'=>'Пользователя с такими email не существует'
            ];
        }//if

        $user->isAdmin = filter_var($user->isAdmin, FILTER_VALIDATE_INT);


        //данные для сессии

        $userForCookie = [
            'userId'=>$user->id
        ];

        $verifyPass = $bcrypt->verify($userPass, $user->userPassword);


        if(!$verifyPass){
            return [
                'code'=>401,
                'message'=>"Неверный пароль"
            ];
        }//if not verify password

        $verifyEmail = $user->verification;

        if(!$verifyEmail){

            return [
                'code'=>405,
                'message'=>'Пройдите верификацию на почте'
            ];
        }//if пользователь не верифицировался

        $userSerializeResult = serialize($userForCookie);

        //если пользователь не админ
        if($user->isAdmin !==1){

            setcookie(
                'user',
                $userSerializeResult,
                time()+60*60*24*60
            );
        }//if user
        else{
            setcookie(
                'admin',
                $userSerializeResult,
                time()+60*30
            );
        }//else admin

        return [
            'code'=>200,
            'message'=>'Авторизация успешна'
        ];
    }//AuthoriseUsers

    public function getCurrentUser(){

        $user = null;

        if(isset($_COOKIE['user'])){
            $user = unserialize($_COOKIE['user']);
        }
        else if(isset($_COOKIE['admin'])){
            $user = unserialize($_COOKIE['admin']);
        }

        return $user;
    }//getCurrentUser

    public function getSingleUser($userID){

        $stm = MySQL::$db->prepare("SELECT id, isAdmin, userEmail, userPassword, verification, userName 
                                    FROM users 
                                    WHERE id= :userID");
        $stm->bindParam(':userID', $userID, \PDO::PARAM_INT);

        $stm->execute();

        return $stm->fetch(\PDO::FETCH_OBJ);
    }
}