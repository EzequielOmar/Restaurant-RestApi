<?php

require_once './db/AccesoDatos.php';

class Usuario{
    public $id;
    public $nombre;
    public $apellido;
    public $clave;
    public $sector;
    public $fecha_ing;
    public $cant_op;
    public $estado;

    static function GetUsuarios(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario"):null;		
    }
    function GuardarUsrBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO usuario(nombre,apellido,clave,sector,fecha_ing,cant_op,estado) 
        VALUES (:nombre,:apellido,:clave,:sector,:fecha_ing,:cant_op,:estado)");
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->clave,PDO::PARAM_STR);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':fecha_ing',$this->fecha_ing,PDO::PARAM_STR);
        $consulta->bindValue(':cant_op',$this->cant_op,PDO::PARAM_STR);
        $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
        return $consulta->execute();
    }
}
?>