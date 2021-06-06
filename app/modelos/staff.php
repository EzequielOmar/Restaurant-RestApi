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
}