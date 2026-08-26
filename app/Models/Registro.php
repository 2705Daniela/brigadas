<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'brigadista_id', 'brigada_id', 'rol_deseado', 'modalidad', 
        'talla', 'siss', 'participacion_anterior', 'municipio_anterior', 'status', 'comentario'
    ];

    public function brigadista() {
        return $this->belongsTo(Brigadista::class);
    }

    public function brigada() {
        return $this->belongsTo(Brigada::class);
    }
}
