<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operacion extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'operacion';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'operacion', 'id_staff', 'sector', 'fecha'
    ];

    public function staff()
    {
        return $this->hasOne(Staff::class,'id_staff','id');
    }
}