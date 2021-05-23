<?php
require_once './db/AccesoDatos.php';

class Cliente{
    public $id;
    public $mail;
    public $nombre;
    public $apellido;
    private $_clave;
    public $cel;
    public $fecha_ing;

    function getClave(){
        return $this->_clave;
    }

    function setClave($value){
        $this->_clave = $value;
    }

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from cliente");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "cliente"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO cliente(mail,nombre,apellido,clave,cel,fecha_ing) 
        VALUES (:mail,:nombre,:apellido,:clave,:cel,:fecha_ing)");
        $consulta->bindValue(':mail',$this->mail,PDO::PARAM_STR);
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->getClave(),PDO::PARAM_STR);
        $consulta->bindValue(':cel',$this->cel,PDO::PARAM_STR);
        $consulta->bindValue(':fecha_ing',$this->fecha_ing,PDO::PARAM_STR);
        return $consulta->execute();
    }
    static function ObtenerPorID(int $id){
        if($id <= 0)
            return null;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from cliente where id=:id");
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);
        return $consulta->execute()? $consulta->fetchObject("cliente"):null;		
    }
    static function ObtenerPorMail(string $mail){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from cliente where mail=:mail");
        $consulta->bindValue(':mail',$mail,PDO::PARAM_STR);
        return $consulta->execute()? $consulta->fetchObject("cliente"):null;		
    }
}
?>