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
}
