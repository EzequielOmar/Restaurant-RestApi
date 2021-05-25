<?php
require_once './db/AccesoDatos.php';

class Staff{
    public $id;
    public $dni;
    public $nombre;
    public $apellido;
    private $_clave;
    public $sector;
    public $fecha_ing;
    public $estado;

    function getClave(){
        return $this->_clave;
    }

    function setClave($value){
        $this->_clave = $value;
    }

    static function GetArrayObj(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select * from staff");
        return $consulta->execute()? $consulta->fetchAll(PDO::FETCH_CLASS, "staff"):null;		
    }
    function GuardarBD(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
        INSERT INTO staff(dni,nombre,apellido,clave,sector,fecha_ing,estado) 
        VALUES (:dni,:nombre,:apellido,:clave,:sector,:fecha_ing,:estado)");
        $consulta->bindValue(':dni',$this->dni,PDO::PARAM_STR);
        $consulta->bindValue(':nombre',$this->nombre,PDO::PARAM_STR);
        $consulta->bindValue(':apellido',$this->apellido,PDO::PARAM_STR);
        $consulta->bindValue(':clave',$this->getClave(),PDO::PARAM_STR);
        $consulta->bindValue(':sector',$this->sector,PDO::PARAM_INT);
        $consulta->bindValue(':fecha_ing',$this->fecha_ing,PDO::PARAM_STR);
        $consulta->bindValue(':estado',$this->estado,PDO::PARAM_STR);
        return $consulta->execute();
    }
    static function ObtenerPorID(int $id){
        if($id <= 0)
            return null;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from staff where id=:id");
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);
        return $consulta->execute()? $consulta->fetchObject("staff"):null;		
    }
    static function ObtenerPorDni(string $dni){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from staff where dni=:dni");
        $consulta->bindValue(':dni',$dni,PDO::PARAM_STR);
        return $consulta->execute()? $consulta->fetchObject("staff"):null;		
    }
}
?>