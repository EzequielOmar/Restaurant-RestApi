<?php

require_once './db/AccesoDatos.php';

class Pedido{
    static public $path_fotos = "./uploads/";

    public $id;
    public $codigo;
    public $codigo_mesa;
    public $estado;
    public $id_producto;
    public $cantidad;
    public $id_mozo;
    public $id_elaborador;
    public $fecha;
    public $hora_comandado;
    public $hora_estimada;
    public $hora_entregado;
    public $hora_cierre;

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from pedido");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "pedido"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO pedido(codigo,codigo_mesa,estado,id_producto,
        cantidad,id_mozo,id_elaborador,fecha,hora_comandado) 
        VALUES (:codigo,:codigo_mesa,:estado,:id_producto,
        :cantidad,:id_mozo,:id_elaborador,:fecha,:hora_comandado)");
        $consulta->bindValue(':codigo',$this->codigo,PDO::PARAM_STR);
        $consulta->bindValue(':codigo_mesa',$this->codigo_mesa,PDO::PARAM_STR);
        $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
        $consulta->bindValue(':id_producto',$this->id_producto,PDO::PARAM_INT);
        $consulta->bindValue(':cantidad',$this->cantidad,PDO::PARAM_INT);
        $consulta->bindValue(':id_mozo',$this->id_mozo,PDO::PARAM_INT);
        $consulta->bindValue(':id_elaborador',$this->id_elaborador,PDO::PARAM_INT);
        $consulta->bindValue(':fecha',$this->fecha,PDO::PARAM_STR);
        $consulta->bindValue(':hora_comandado',$this->hora_comandado,PDO::PARAM_STR);
        return $consulta->execute();
    }
    static function ObtenerPorCodigo($codigo){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from pedido where codigo=:codigo");
        $consulta->bindValue(':codigo',$codigo,PDO::PARAM_STR);
        return $consulta->execute()? $consulta->fetchObject("Pedido"):null;		
    }
}
?>