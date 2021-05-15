<?php
require_once './db/AccesoDatos.php';

class Mesa{
    public $id;
    public $codigo;
    public $estado;

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from mesa");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO mesa(codigo,estado) VALUES (:codigo,:estado)");
        $consulta->bindValue(':codigo',$this->codigo,PDO::PARAM_STR);
        $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
        return $consulta->execute();
    }
    static function ObtenerPorCodigo($codigo_mesa){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select 1 from mesa where codigo=:codigo");
        $consulta->bindValue(':codigo',$codigo_mesa,PDO::PARAM_STR);
        return $consulta->execute()? $consulta->fetchObject("Mesa"):null;		
    }
}
?>