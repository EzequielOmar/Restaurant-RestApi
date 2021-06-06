<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Cliente extends Model
{
    use SoftDeletingTrait;
    protected $primaryKey = 'id';
    protected $table = 'cliente';
    public $incrementing = true;
    public $timestamps = true;
    const CREATED_AT = 'fecha_ing';
    const UPDATED_AT = 'fecha_modif';
    const DELETED_AT = 'fecha_baja';
    protected $fillable = [
        'mail',
        'nombre',
        'apellido',
        'clave',
        'cel',
        'fecha_ing',
        'fecha_modif',
        'fecha_baja'
    ];
}
