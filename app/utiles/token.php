<?php
require_once '../vendor/autoload.php';
use Firebase\JWT;

class Token{
    private static $enc = ['HS256'];
    private static $aud = null;
    
    public static function Crear($datos){
        $ahora = time();
        $payload = array(
        	'iat'=>$ahora,
            'exp' => $ahora + (60*60),
            'aud' => self::Aud(),
            'data' => $datos,
            'app'=> "Comanda 2021"
        );
        return JWT\JWT::encode($payload,$_ENV['JWT_KEY']);
    }
    
    public static function Verificar($token){
        if(empty($token)|| $token=="")
            throw new Exception("El token esta vacio.");
        try {
            $decodificado = JWT\JWT::decode(
            $token,
            $_ENV['JWT_KEY'],
            self::$enc
            );
        } catch (JWT\ExpiredException $e){
           throw new Exception("Clave fuera de tiempo");
        }
        if($decodificado->aud !== self::Aud())
            throw new Exception("No es el usuario valido");
    }

    public static function ObtenerData($token){
        return JWT\JWT::decode(
            $token,
            $_ENV['JWT_KEY'],
            self::$enc
        )->data;
    }

    private static function Aud(){
        $aud = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        return sha1($aud);
    }
}