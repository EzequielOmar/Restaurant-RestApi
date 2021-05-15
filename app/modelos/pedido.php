<?php

require_once './db/AccesoDatos.php';

class Pedido{
    public $id;
    public $nombre;
    public $descripcion;
    public $sector;
    public $precio;
    public $stock;

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from pedido");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "pedido"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO pedido(nombre,descripcion,sector,precio,stock) 
        VALUES (:nombre,:descripcion,:sector,:precio,:stock)");
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':descripcion',$this->descripcion,PDO::PARAM_STR);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_STR);
        $consulta->bindValue(':precio',$this->precio,PDO::PARAM_STR);
        $consulta->bindValue(':stock',$this->stock,PDO::PARAM_INT);
        return $consulta->execute();
    }
}
?>