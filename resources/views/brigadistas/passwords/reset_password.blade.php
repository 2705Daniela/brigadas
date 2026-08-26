@component('mail::message')
# Hola {{ $notifiable->nombre }} {{ $notifiable->apellido_paterno }} {{ $notifiable->apellido_materno }}

Has solicitado restablecer tu contraseña.

@component('mail::button', ['url' => $url])
Restablecer Contraseña
@endcomponent

Si no realizaste esta solicitud, puedes ignorar este correo.

Atentamente,  
**SOPORTE PLANNASSZE**


Si tienes problemas para hacer clic en el botón "Restablecer Contraseña", copia y pega la siguiente URL en tu navegador: {{ $url }}
@endcomponent
