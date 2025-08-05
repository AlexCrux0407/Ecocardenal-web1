<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioMeta extends Model
{
    use HasFactory;

    protected $table = 'usuario_metas';

    protected $fillable = [
        'usuario_id',
        'meta_id',
        'completada_en',
    ];

    public $timestamps = false;

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación con la meta
     */
    public function meta()
    {
        return $this->belongsTo(Meta::class, 'meta_id');
    }
}