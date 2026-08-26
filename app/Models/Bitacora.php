<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'brigadista_id',
        'accion',
        'observaciones',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function brigadista()
    {
        return $this->belongsTo(Brigadista::class, 'brigadista_id');
    }

    public static function registrar($accion, $brigadistaId = null, $observaciones = null)
{
    return self::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => $brigadistaId,
        'accion' => $accion,
        'observaciones' => $observaciones,
    ]);
}

}


