<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BrigadistaResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
class Brigadista extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nombre de la tabla (opcional si sigue la convención "brigadistas")
    protected $table = 'brigadistas';
    public function statusSalud()
{
    return $this->hasOne(StatusSalud::class);
}


    protected $fillable = [
        // Datos del brigadista
        'numero_boleta',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'curp',
        'unidad_academica',
        'programa_academico',
        'especialidad',
        'situacion_academica',
        'institucion_ss',
        'nss',
        'telefono',
        'correo',
        'password',
        'password_real',
        'calle',
        'no_int',
        'no_ext',
        'colonia',
        'municipio',
        'estado',
        'cp',
        'clabe',
        'banco',
        'genero',
        'edad',
        'foto',


        // Contacto de emergencia
        'contacto_nombre',
        'contacto_parentesco',
        'calle_contacto',
        'no_int_contacto',
        'no_ext_contacto',
        'colonia_contacto',
        'municipio_contacto',
        'estado_contacto',
        'cp_contacto',
        'telefono_contacto',

        // Firma y folio
        'firma',
        'tipo_firma',

        //status de salud
        'status_completado',       
    ];

public function bitacoras()
{
    return $this->hasMany(Bitacora::class);
}

public function getModificoAttribute()
{
    return $this->bitacoras()->latest()->first()?->usuario?->name;
}

// Brigadista.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function archivos()
{
    return $this->hasMany(Archivo::class);
}

public function asignaciones()
{
    return $this->hasMany(Asignacion::class);
}

public function brigadas()
{
    return $this->belongsToMany(
        Brigada::class,   
        'registros',      
        'brigadista_id',   
        'brigada_id'      
    );
}

public function registros()
{
    return $this->hasMany(Registro::class);
}

public function getEmailForPasswordReset()
{
    return $this->correo;
}
 public function sendPasswordResetNotification($token)
    {
        $this->notify(new BrigadistaResetPasswordNotification($token));
    }
 public function routeNotificationForMail($notification)
    {
        return $this->correo; // tu columna real
    }

public function asignacion()
{
    return $this->hasOne(Asignacion::class, 'brigadista_id', 'id');
}

}


