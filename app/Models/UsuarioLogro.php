<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioLogro extends Model
{
    use HasFactory;

    protected $table = 'usuario_logros';

    protected $fillable = [
        'usuario_id',
        'logro_id',
        'desbloqueado_en',
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
     * Relación con el logro
     */
    public function logro()
    {
        return $this->belongsTo(Logro::class, 'logro_id');
    }
}