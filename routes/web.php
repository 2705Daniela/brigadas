<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrigadistaController;
use App\Http\Controllers\BrigadaController;
use App\Http\Controllers\StatusSaludController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExportController;
use Mews\Captcha\Facades\Captcha;
use App\Http\Controllers\BrigadistaAuthController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\Admin\FiltrosController;
use App\Models\EstadoMunicipio;

// Ruta para la pagina principal
Route::get('/', [BrigadistaController::class, 'landing'])->name('brigadistas.landing');

//Rutas para el Registro de brigadista
Route::get('/brigadistas/create', [BrigadistaController::class, 'create'])->name('brigadistas.create');
Route::post('/brigadistas', [BrigadistaController::class, 'store'])->name('brigadistas.store');
Route::get('/brigadistas/acciones/{id}', [BrigadistaController::class, 'acciones'])->name('brigadistas.acciones');
Route::get('/brigadista/login', [BrigadistaController::class, 'login'])->name('login_brigadista');

//Ruta definida para buscar alumno por boleta, mediante el servicio (SOAP)
Route::post('/buscar-alumno', [AlumnoController::class, 'buscar'])->name('buscar.alumno');

// Rutas de Acciones post-registro
Route::get('/brigadistas/acciones/{id}', [BrigadistaController::class, 'acciones'])->name('brigadistas.acciones');

//Rutas para firma
Route::get('/brigadistas/{brigadista}/firma/{documento}', [BrigadistaController::class, 'firma'])->name('brigadistas.firma');
Route::post('/brigadistas/{brigadista}/firmar/{documento}', [BrigadistaController::class, 'firmarPDF'])->name('brigadistas.firmarPDF');

//Ruta para generacion de ficha en pdf
Route::post('/brigadistas/ficha/pdf', [BrigadistaController::class, 'generarFichaPDF'])->name('brigadistas.generarFichaPDF');


// Consultar status
//Route::post('/brigadistas/consultar', [BrigadistaController::class, 'consultarStatus'])->name('brigadistas.consultar');

// Rutas para generar documentos (Después de firmar)
Route::post('/brigadistas/ficha', [BrigadistaController::class, 'generarFicha'])->name('brigadistas.generarFicha');
Route::post('/brigadistas/carta', [BrigadistaController::class, 'generarCarta'])->name('brigadistas.generarCarta');
Route::post('/brigadistas/status', [BrigadistaController::class, 'generarStatus'])->name('brigadistas.generarStatus');

// Rutas para ver documentos en navegador 
Route::get('/brigadistas/{id}/ver-ficha', [BrigadistaController::class, 'verFicha'])->name('brigadistas.verFicha');
Route::get('/brigadistas/{id}/ver-carta', [BrigadistaController::class, 'verCarta'])->name('brigadistas.verCarta');
Route::get('/brigadistas/{id}/ver-status', [BrigadistaController::class, 'verStatus'])->name('brigadistas.verStatus');

//Ruta para mostrar formulario de status de salud
Route::get('/brigadistas/{id}/status-salud', [BrigadistaController::class, 'mostrarStatusSalud'])
   ->name('brigadistas.statusSalud');

// Ruta POST para guardar el status de salud
Route::post('brigadistas/{id}/status-salud', [StatusSaludController::class, 'guardarStatusSalud'])
    ->name('brigadistas.guardarStatusSalud');
Route::get('/brigadistas/{id}/status', [StatusSaludController::class, 'mostrarStatusSalud'])
    ->name('status.mostrar');
Route::post('/brigadistas/{id}/status', [StatusSaludController::class, 'guardarStatusSalud'])
    ->name('status.guardar');

// Ruta GET para mostrar el formulario de status de salud
Route::get('brigadistas/{id}/status-salud', [StatusSaludController::class, 'mostrarStatusSalud'])
    ->name('brigadistas.statusSalud');

Route::get('brigadistas/contacto', function () {
    return view('brigadistas.contacto');
})->name('brigadistas.contacto');

// Solo administrador
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});


// Ruta para mostrar formulario de login admin
Route::get('/admin/login', [AuthController::class, 'mostrarLogin'])->name('admin.login');
//Ruta para procesar login admin
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
// Logout admin
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


// Ruta de dashboard (solo después de login)
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); // Crea la vista admin.dashboard
    })->name('admin.dashboard');
});

//Rutas definidas para el modulo usuarios
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:Administrador'])
    ->group(function () {

        // Lista de usuarios
        Route::get('/usuarios', [UserController::class, 'index'])
            ->name('usuarios.index');

        // Crear usuario
        Route::get('/usuarios/crear', [UserController::class, 'create'])
            ->name('usuarios.create');

        Route::post('/usuarios', [UserController::class, 'store'])
            ->name('usuarios.store');

        // Editar usuario
        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])
            ->name('usuarios.edit');

        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])
            ->name('usuarios.update');

        // Eliminar usuario
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])
            ->name('usuarios.destroy');

        // 2FA
        Route::get('/usuarios/{usuario}/2fa', [UserController::class, 'show2FA'])
            ->name('usuarios.2fa.show');

        Route::put('/usuarios/{usuario}/enable-2fa', [UserController::class, 'enable2FA'])
            ->name('usuarios.enable2fa');

        Route::put('/usuarios/{usuario}/disable-2fa', [UserController::class, 'disable2FA'])
            ->name('usuarios.disable2fa');
    });


Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/brigadistas/asistencias', [AdminController::class, 'asistencias'])
            ->name('brigadistas.asistencias');

        Route::get('/brigadistas/lista_municipios', [AdminController::class, 'listaMunicipios'])
            ->name('brigadistas.lista_municipios');

        Route::post('/logout', [AdminController::class, 'logout'])
            ->name('logout');
    });

//Rutas definidas para las accion es realizadas en la pantalla gestionar
Route::prefix('admin/brigadistas')->name('admin.brigadistas.')->group(function () {
    Route::get('/', [BrigadistaController::class, 'gestionar'])->name('gestionar');
    Route::get('{id}/validar', [BrigadistaController::class, 'mostrarValidar'])->name('mostrarValidar');
    Route::post('{id}/validar', [BrigadistaController::class, 'validarSubmit'])->name('validar.submit');;
    Route::post('{id}/rechazar', [BrigadistaController::class, 'rechazar'])->name('rechazar');
    Route::post('{id}/cancelar', [BrigadistaController::class, 'cancelar'])->name('cancelar');
    Route::delete('{id}/eliminar', [BrigadistaController::class, 'eliminar'])->name('eliminar');
});

// Rutas para administracion y filtros AJAX
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/brigadistas/gestionar', [AdminController::class, 'gestionarBrigadistas'])
        ->name('gestionar'); 
    // Filtro AJAX 
    Route::get('/brigadistas/filtrar', [FiltrosController::class, 'filtrar'])
        ->name('gestionar.filtrar');
});

//Rutas para administracion de registros y filtros AJAX
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/registros-completo', [AdminController::class, 'registrosCompleto'])
        ->name('registros_completo');

    Route::get('/admin/registros-completo/filtrar', [FiltrosController::class, 'filtrarRegistrosCompletos'])
    ->name('registros_completo.filtrar');
});


//Ruta definida para exportar EXCEL
Route::get('/admin/brigadistas/export', [ExportController::class, 'export'])
    ->name('admin.brigadistas.export');


//Rutas para CAPTCHA
Route::get('captcha/{config?}', function (\Illuminate\Http\Request $request, $config = 'default') {
    return Captcha::create($config);
});
Route::get('/captcha/reload', function () {
    return response()->json(['captcha'=> captcha_img()]);
});

//Rutas definidas para el login del brigadista
Route::get('/login-brigadista', [BrigadistaController::class, 'showLogin'])->name('brigadista.login');
Route::post('/login-brigadista', [BrigadistaController::class, 'login'])->name('brigadista.login.submit');
Route::post('/logout-brigadista', [BrigadistaController::class, 'logout'])->name('brigadista.logout');

//Rutas definidas para el objeto "BRIGADA"
// Formulario para crear brigada
Route::get('admin/crear_brigada', [BrigadaController::class, 'create'])
    ->name('admin.crear_brigada');
// Guardar brigada
Route::post('admin/crear_brigada', [BrigadaController::class, 'store'])
    ->name('admin.crear_brigada.store');
// Gestionar brigadas
Route::get('admin/gestionar_brigadas', [BrigadaController::class, 'gestionar'])
    ->name('admin.gestionar_brigada');
    // Mostrar formulario de edición
Route::get('/admin/editar_brigada/{id}', [App\Http\Controllers\BrigadaController::class, 'edit'])
    ->name('admin.editar_brigada');
// Guardar actualización
Route::put('/admin/editar_brigada/{id}', [App\Http\Controllers\BrigadaController::class, 'update'])
    ->name('admin.editar_brigada.update');

Route::delete('/admin/brigadas/{id}', [AdminController::class, 'eliminarBrigada'])
    ->name('admin.eliminar_brigada');
    
//Rutas definidas para el registro de un brigadista a una brigada activa    
//Registrar brigada
Route::post('/brigadistas/registrar-brigada', [BrigadistaController::class, 'registrarBrigada'])
    ->name('brigadistas.registrarBrigada');
//Actuslizar contacto de emergencia en caso de ser necesario
Route::post('/brigadistas/{brigadista}/actualizarContacto', [BrigadistaController::class, 'actualizarContacto'])
    ->name('brigadistas.actualizarContacto');
//Guarda registro de la brigada
Route::post('/brigadista/{brigadista}/registro', [BrigadistaController::class, 'actualizarRegistro'])
    ->name('brigadistas.actualizarRegistro');
Route::delete('/brigadista/registros/{id}', [BrigadistaController::class, 'eliminarRegistro'])->name('registros.eliminar');


// Rutas definidas para la recuperación de contraseña (brigadista)
  Route::prefix('brigadistas')->group(function () {
    // Formulario "Olvidé mi contraseña"
    Route::get('password/reset', [BrigadistaAuthController::class, 'showLinkRequestForm'])
        ->name('brigadistas.password.request');
    // Enviar correo automático
    Route::post('password/auto', [BrigadistaAuthController::class, 'sendResetLinkAuto'])
        ->name('brigadistas.password.auto');
    // Formulario para restablecer contraseña
    Route::get('password/reset/{token}', [BrigadistaAuthController::class, 'showResetForm'])
        ->name('brigadistas.password.reset');
    // Actualizar contraseña
    Route::post('password/reset', [BrigadistaAuthController::class, 'reset'])
        ->name('brigadistas.password.update');
});

// Rutas definidas para poder validar, cancelar y rechazar registros desde gestionar.
Route::post('/admin/brigadistas/acciones-masivas', [BrigadistaController::class, 'accionesMasivas'])
    ->name('admin.brigadistas.accionesMasivas');


Route::get('/admin/brigadistas/lista', [BrigadistaController::class, 'lista'])
    ->name('admin.brigadistas.lista'); 
Route::get('/admin/lista-brigadistas/filtrar', [FiltrosController::class, 'filtrarListaBrigadistas'])
    ->name('admin.lista.filtros');

Route::post('/admin/brigadistas/verificar-password', [BrigadistaController::class, 'verificarPassword'])
    ->name('admin.verificarPassword');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


//Rutas definidas para la recuperacion de contraseñas (Administrador)
// Mostrar formulario
Route::get('/admin/brigadistas/{id}/restablecer', [AdminController::class, 'mostrarRestablecerPassword'])
    ->name('admin.brigadistas.restablecer');
// Actualizar contraseña
Route::post('/admin/brigadistas/{id}/restablecer', [AdminController::class, 'restablecerPassword'])
    ->name('admin.brigadistas.restablecer.update');


//Rutas definidas para cargar en los select los estados y municipios cargados a 
// travez de un archivo CSV
// Devuelve los municipios de un estado específico
Route::get('/municipios/{estado}', [AdminController::class, 'obtenerMunicipios']);


//Rutas definidas para la asignacion del brigadista
Route::prefix('admin')->group(function() {
    // Filtrar asignaciones (AJAX)
    Route::get('asignaciones/filtrar', [FiltrosController::class, 'filtrarAsignaciones'])
        ->name('admin.asignaciones.filtrar');
    Route::get('asignaciones/{brigada?}', [AdminController::class, 'asignaciones'])
    ->name('admin.asignaciones');
    // Guardar todas las asignaciones
    Route::post('asignaciones/guardar', [AdminController::class, 'guardarAsignaciones'])
        ->name('admin.asignaciones.guardar');
    // Guardar una fila individual (AJAX)
    Route::post('asignaciones/guardar-fila', [AdminController::class, 'guardarFila'])
        ->name('admin.asignaciones.guardarFila');
});


//Rutas definidas para las listas de asistencia
Route::prefix('admin')->name('admin.')->group(function () {
    // Vista de listas de asistencia con filtros
    Route::get('/lista-asistencia', [AdminController::class, 'listasAsistencia'])
        ->name('lista_asistencia');
    //Filtro AJAX
    Route::get('/lista-asistencia/filtrar', [FiltrosController::class, 'filtrarListaAsistencia'])
        ->name('lista_asistencia.filtrar');
    // Generar PDF de listas de asistencia
    Route::get('/lista-asistencia/pdf', [AdminController::class, 'descargarListaPDF'])
        ->name('lista_asistencia.pdf');
});

//Ruta para ver la vista de bitacora
Route::get('/admin/bitacoras', [AdminController::class, 'mostrarBitacoras'])->name('admin.bitacoras');

//Rutas definidas para que el administrador/departamento pueda registrar manualmente a un brigadista
// Mostrar formulario para registrar brigadista
Route::get('/admin/registrar_brigadista', [AdminController::class, 'create'])
    ->name('admin.brigadistas.create');
// Guardar brigadista en la base de datos
Route::post('/admin/registrar_brigadista', [AdminController::class, 'storeBrigadista'])
    ->name('admin.brigadistas.store');

//Rutas definidas para crear supervisores
Route::middleware(['auth', 'role:Administrador'])->group(function () {

    // Registrar supervisores
    Route::get('/registrar_supervisor', [AdminController::class, 'registrarSupervisor'])
        ->name('supervisores.create');

    Route::post('/registrar_supervisor', [AdminController::class, 'guardarSupervisor'])
        ->name('supervisores.store');

    // Lista de supervisores
    Route::get('/admin/lista-supervisores', [AdminController::class, 'listaSupervisores'])
        ->name('admin.lista_supervisores');

    // Asignación de supervisores
    Route::get('/admin/asignacion-supervisores', [AdminController::class, 'asignacionSupervisores'])
        ->name('admin.asignacion_supervisores');

    Route::post('/admin/asignacion-supervisores/guardar-fila', [AdminController::class, 'guardarAsignacionSupervisorFila'])
        ->name('admin.asignaciones_supervisores.guardarFila');

    // Filtro AJAX
    Route::get('/admin/asignacion-supervisores/filtrar', [FiltrosController::class, 'filtrarSupervisores'])
        ->name('admin.asignacion_supervisores.filtrar');
});

Route::get('/supervisores/{id}/editar', [AdminController::class, 'editarSupervisor'])
    ->name('admin.supervisores.editar');

Route::put('/supervisores/{id}', [AdminController::class, 'actualizarSupervisor'])
    ->name('admin.supervisores.actualizar');

Route::delete('/supervisores/{id}', [AdminController::class, 'eliminarSupervisor'])
    ->name('admin.supervisores.eliminar');

//Ruta definida para el autocompletado del domicilio por CP
Route::get('/buscar-cp', [BrigadistaController::class, 'buscarCP'])->name('buscar.cp');

// Ruta definida para el modulo credenciales
Route::get('/admin/credenciales', [AdminController::class, 'credenciales'])->name('admin.credenciales') ->middleware('auth');
Route::get('/admin/credenciales', [AdminController::class, 'credenciales'])
    ->name('admin.credenciales');
Route::get('/admin/credenciales/{id}/pdf', [AdminController::class, 'credencialPdf']
)->name('admin.credencial.pdf');
//Route::get('/admin/credenciales/filtros', [CredencialController::class, 'filtros'])->name('admin.lista.filtros');
Route::get(
    '/admin/credenciales/descargar-seleccionadas',
    [AdminController::class, 'descargarCredencialesSeleccionadas']
)->name('admin.credenciales.descargar.seleccionadas');

