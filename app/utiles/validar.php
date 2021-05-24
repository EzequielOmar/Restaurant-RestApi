<?php
require_once './modelos/staff.php';
require_once './modelos/producto.php';
require_once './modelos/pedido.php';
require_once './modelos/mesa.php';

class Validar{

    private static function HashClave(string $clave){
        return hash("sha1",$clave);
    }
    private static function ChequearSector(string $sector){
        switch($sector){
            case "bar":case "cerveza":case "cocina":case "mozo":case "socio":
                return true;
        }
    }
    private static function ChequearPedido(Pedido $ped){
        if(!ctype_alnum($ped->codigo)||strlen($ped->codigo) != 5)
            return "No corresponde el formato del código.";
        $mesa = Mesa::ObtenerPorCodigo($ped->codigo_mesa);
        if(empty($mesa)||$mesa->estado == "cerrada")
            return "No existe una mesa con ese código.";
        $prod = Producto::ObtenerPorID($ped->id_producto);
        if(empty($prod)||$prod->stock<$ped->cantidad){
            var_dump($prod);
            var_dump($ped->cantidad);
            return "No tenemos stock para realizar el pedido.";}
        $mozo = Staff::ObtenerPorID($ped->id_mozo);
        if(empty($mozo)||$mozo->sector != "mozo")
            return "No hay personal para tomar el pedido.";
    }
    /**
     * Recibe la variable que contiene los params,
     * los valida, y retorna un Staff con los datos, o un string con el error.
     */
    public static function Staff($params){
        $dni= $params['dni']?? null;
        $nombre= $params['nombre']?? null;
        $apellido= $params['apellido'] ?? null;
        $clave= $params['clave'] ?? null;
        $sector= $params['sector'] ?? null;
        if(empty($dni)||empty($nombre)||empty($apellido)||empty($clave)||empty($sector))
            return "Error, faltan datos.";
        $stf = new Staff();
        $stf->dni=str_replace('.','',trim($dni));
        $stf->nombre=ucwords(strtolower(trim($nombre)));
        $stf->apellido=ucwords(strtolower(trim($apellido)));
        $stf->setClave(self::HashClave($clave));
        $stf->sector=strtolower(trim($sector));
        $dt = new DateTime("now",new DateTimeZone("America/Argentina/Buenos_Aires"));
        $stf->fecha_ing = $dt->format('Y-m-d H-i-s');
        $stf->estado = "activo";
        if(!is_numeric($stf->dni)||!ctype_alpha($stf->nombre)||!ctype_alpha($stf->apellido))
            return "Error, formato incorrecto.";
        if(!self::ChequearSector($stf->sector))
            return "No corresponde el sector.";
        if(Staff::ObtenerPorDni($stf->dni))
            return "Ya existe el empleado con dni nro. ".$stf->dni.".";
        return $stf;
    }
    /**
     * Recibe la variable que contiene los params,
     * los valida, y retorna un Cliente con los datos, o un string con el error.
     */
    public static function Cliente($params){
        $mail= $params['mail']?? null;
        $nombre= $params['nombre']?? null;
        $apellido= $params['apellido'] ?? null;
        $clave= $params['clave'] ?? null;
        $cel= $params['cel'] ?? null;
        if(empty($mail)||empty($nombre)||empty($apellido)||empty($clave)||empty($cel))
            return "Error, faltan datos.";
        $cli = new Cliente();
        $cli->mail=trim($mail);
        $cli->nombre=ucwords(strtolower(trim($nombre)));
        $cli->apellido=ucwords(strtolower(trim($apellido)));
        $cli->setClave(self::HashClave($clave));
        $cli->cel=trim($cel);
        $dt = new DateTime("now",new DateTimeZone("America/Argentina/Buenos_Aires"));
        $cli->fecha_ing = $dt->format('Y-m-d H-i-s');
        if(!str_contains($cli->mail,"@")||!str_contains($cli->mail,".com")
            ||!ctype_alpha($cli->nombre)||!ctype_alpha($cli->apellido) ||
            !is_numeric($cli->cel) || strlen($cli->cel) < 10)
            return "Error, formato incorrecto.";
        if(Cliente::ObtenerPorMail($cli->mail))
            return "Ya existe un cliente con el mail ".$cli->mail.".";
        return $cli;
    }
    /**
     * Recibe la variable que contiene los params,
     * los valida, y retorna un Producto con los datos, o un string con el error.
     */
    public static function Producto($params){
        $nombre= $params['nombre']?? null;
        $descripcion= $params['descripcion'] ?? null;
        $sector= $params['sector'] ?? null;
        $precio= $params['precio'] ?? null;
        $stock= $params['stock'] ?? null;
        if(empty($nombre)||empty($descripcion)||empty($sector)||empty($precio)||empty($stock))
            return "Error, datos faltantes.";
        $prod = new Producto();
        $prod->nombre=ucfirst(strtolower(trim($nombre)));
        $prod->descripcion=ucfirst(strtolower(trim($descripcion)));
        $prod->sector=strtolower(trim($sector));
        $prod->precio = '$'.str_replace(',','.',$precio);
        $prod->stock = $stock;
        if(!is_numeric($precio)||!is_numeric($stock))
            return "Error de formato al cargar datos";
        if(!self::ChequearSector($prod->sector))
            return "No corresponde el sector.";
        if(Producto::ObtenerPorNombre($prod->nombre))
            return "Ya existe un producto con el nombre: ".$prod->nombre.".";
        return $prod;
    }
    /**
     * Recibe la variable que contiene los params,
     * los valida, y retorna un Pedido con los datos, o un string con el error.
     */
    public static function Pedido($params){
        $codigo= $params['codigo']?? null;
        $codigo_mesa= $params['codigo_mesa'] ?? null;
        $id_producto= $params['id_producto'] ?? null;
        $cantidad= $params['cantidad'] ?? null;
        $id_mozo= $params['id_mozo'] ?? null;
        if(empty($codigo)||empty($codigo_mesa)||empty($id_producto)||
           empty($cantidad)||empty($id_mozo))
            return "Error, datos faltantes.";
        $ped = new Pedido();
        $ped->codigo = trim($codigo);
        $ped->codigo_mesa = trim($codigo_mesa);
        $ped->estado = "comandado";
        $ped->id_producto = $id_producto;
        $ped->cantidad = $cantidad;
        $ped->id_mozo = $id_mozo;
        $ped->id_elaborador = 0;
        $dt = new DateTime("now",new DateTimeZone("America/Argentina/Buenos_Aires"));
        $ped->fecha = $dt->format('Y-m-d');
        $ped->hora_comandado = $dt->format('H:i:s');
        $error = self::ChequearPedido($ped);  
        if(!empty($error))
            return $error;
        if(!empty($_FILES["foto-mesa"]) && $_FILES["foto-mesa"]["error"] == 0)
            move_uploaded_file($_FILES["foto-mesa"]["tmp_name"],Pedido::$path_fotos.$ped->codigo.".jpg");
        return $ped;
    }
    /**
     * Recibe la variable que contiene los params,
     * los valida, y retorna una Mesa con los datos, o un string con el error.
     */
    public static function Mesa($params){
        $codigo= $params['codigo']?? null;
        if(empty($codigo))
            return "Error, datos faltantes.";
        $mesa = new Mesa();
        $mesa->codigo=trim($codigo);
        $mesa->estado="abierta";
        if(!ctype_alnum($mesa->codigo)||strlen($mesa->codigo) != 5)
            return "No corresponde el formato código.";
        if(Mesa::ObtenerPorCodigo($mesa->codigo))
            return "Ya existe una mesa con el codigo ".$mesa->codigo.".";
        return $mesa;
    }
    /**
     * Recibe la variable que contiene los params,
     * valida que exista el mail, que coincidan las claves y retorna un Cliente
     */
    public static function logCliente($params){
        $mail= $params['mail']?? null;
        $clave= $params['clave'] ?? null;
        $cli = Cliente::ObtenerPorMail($mail);
        if(empty($cli))
            return "No existe un usuario con ese mail. Regístrese.";
        if($cli->clave != Validar::HashClave($clave))
            return "Contraseña incorrecta.";
        return $cli; 
    }
    /**
     * Recibe la variable que contiene los params,
     * valida que exista el dni, que coincidan las claves y retorna un Staff
     */
    public static function logStaff($params){
        $dni= $params['dni']?? null;
        $clave= $params['clave'] ?? null;
        $staff = Staff::ObtenerPorDni($dni);
        if(empty($staff))
            return "No existe un usuario con ese mail. Regístrese.";
        if($staff->clave != Validar::HashClave($clave))
            return "Contraseña incorrecta.";
        return $staff; 
    }
}
?>