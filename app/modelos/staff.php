<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Staff extends Model
{
    use SoftDeletingTrait;
    protected $primaryKey = 'id';
    protected $table = 'staff';
    public $incrementing = true;
    public $timestamps = false;
    const DELETED_AT = 'fecha_baja';
    protected $fillable = [
        'dni', 'nombre', 'apellido', 'clave', 'sector', 'fecha_ing', 'estado', 'fecha_baja'
    ];

    public function mesas()
    {
        return $this->hasMany(Mesa::class,'id_mozo_asignado','id');
    }

    public function pedidosMozo()
    {
        return $this->hasMany(Pedido::class,'id_mozo','id');
    }

    public function pedidosElaborador()
    {
        return $this->hasMany(Pedido::class,'id_elaborador','id');
    }
}