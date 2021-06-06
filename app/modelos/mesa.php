<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'mesa';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'codigo', 'estado', 'id_mozo_asignado'
    ];

    public function mozo()
    {
        return $this->hasOne(Staff::class,'id','id_mozo_asignado');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class,'codigo_mesa','codigo');
    }
    
    public function facturas()
    {
        return $this->hasMany(Factura::class,'id_mesa','id');
    }
        
    public function comentarios()
    {
        return $this->hasMany(Comentario::class,'id_mesa','id');
    }
}
