<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model{
    protected $primaryKey = 'id';
    protected $table = 'factura';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id_mesa', 'id_pedido', 'id_cliente', 'monto', 'fecha'
    ];

    public function mesa()
    {
        return $this->hasOne(Mesa::class,'id','id_mesa');
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class,'id','id_pedido');
    }
    
    public function cliente()
    {
        return $this->hasOne(Pedido::class,'id','id_cliente');
    }
}
?>