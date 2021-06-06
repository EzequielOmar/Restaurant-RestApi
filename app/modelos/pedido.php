<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Pedido extends Model{
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
}
?>