<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Pedido extends Model
{
    use SoftDeletingTrait;
    protected $primaryKey = 'id';
    protected $table = 'pedido';
    public $incrementing = true;
    public $timestamps = false;
    const DELETED_AT = 'hora_cierre';
    protected $fillable = [
        'codigo',
        'codigo_mesa',
        'estado',
        'id_producto',
        'cantidad',
        'id_mozo',
        'id_elaborador',
        'fecha',
        'hora_comandado',
        'hora_tomado',
        'hora_estimada',
        'hora_listo',
        'hora_entregado',
        'hora_cierre'
    ];

    public function mesa()
    {
        return $this->hasOne(Mesa::class, 'codigo', 'codigo_mesa');
    }

    public function producto()
    {
        return $this->hasOne(Producto::class, 'id', 'id_producto');
    }

    public function mozo()
    {
        return $this->hasOne(Staff::class, 'id', 'id_mozo');
    }

    public function elaborador()
    {
        return $this->hasOne(Staff::class, 'id', 'id_elaborador');
    }

    public function factura()
    {
        return $this->hasOne(Factura::class, 'id_pedido', 'id');
    }
}
