<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model{  
    protected $primaryKey = 'id';
    protected $table = 'comentario';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id_mesa', 'rate_mesa', 'rate_rest', 'rate_mozo', 'rate_cocina', 'comentario', 'fecha'
    ];

    public function mesa()
    {
        return $this->hasOne(Mesa::class,'id','id_mesa');
    }
}
?>