<?php

require_once './db/AccesoDatos.php';

class Usuario{
    public $id;
    public $nombre;
    public $apellido;
    private $_clave;
    public $sector;
    public $fecha_ing;
    public $cant_op;
    public $estado;

    function getClave(){
        return $this->_clave;
    }

    function setClave($value){
        $this->_clave = $value;
    }

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from usuario");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO usuario(nombre,apellido,clave,sector,fecha_ing,cant_op,estado) 
        VALUES (:nombre,:apellido,:clave,:sector,:fecha_ing,:cant_op,:estado)");
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->getClave(),PDO::PARAM_STR);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':fecha_ing',$this->fecha_ing,PDO::PARAM_STR);
        $consulta->bindValue(':cant_op',$this->cant_op,PDO::PARAM_INT);
        $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
        return $consulta->execute();
    }
    static function ObtenerPorID(int $id){
        if($id <= 0)
            return null;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from usuario where id=:id");
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);
        return $consulta->execute()? $consulta->fetchObject("Usuario"):null;		
    }
}
?>