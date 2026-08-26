<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brigadista;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Models\Brigada;
use Illuminate\Support\Facades\Auth;
use App\Models\EstadoMunicipio;
use App\Models\Asignacion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bitacora;
use Carbon\Carbon;
use App\Models\Supervisor;



class AdminController extends Controller
{
    // Mostrar dashboard del admin
  public function dashboard()
{
    $brigadistas = Brigadista::whereHas('registros', function($query){
        $query->whereHas('brigada', function($q){
            $q->where('estado', 1); // Solo brigadas activas
        });
    })->get();

    return view('admin.gestionar', compact('brigadistas'));
}

    // Crear nuevo usuario
    public function crearUsuario()
    {
        return view('admin.usuarios.crear');
    }

    // Editar usuario
    public function editarUsuario()
    {
        $usuarios = User::all();
        return view('admin.usuarios.editar', compact('usuarios'));
    }


    // Gestionar registros de brigadistas
public function gestionarBrigadistas()
{
    // Traer solo brigadistas que tengan registros en brigadas activas
    $brigadistas = Brigadista::whereHas('registros.brigada', function($query){
        $query->where('estado', 1);
    })->get();

    // Traer solo brigadas activas para el filtro
    $brigadas = Brigada::where('estado', 1)->get();

    return view('admin.gestionar', compact('brigadistas', 'brigadas'));
}

public function eliminarBrigada($id)
{
    $brigada = Brigada::findOrFail($id);

    $nombreBrigada = $brigada->brigada;
    $anio = $brigada->año;

    $brigada->delete();

    $this->registrarBitacora(
        'Eliminó brigada',
        "Se eliminó la brigada {$nombreBrigada} ({$anio})."
    );

    return redirect()
        ->route('admin.gestionar_brigada')
        ->with('success', 'Brigada eliminada correctamente.');
}
    // Asignar municipio a brigadistas
public function asignarMunicipio()
    {
        $brigadistas = Brigadista::all();
        return view('admin.brigadistas.asignar_municipio', compact('brigadistas'));
    }

    // Lista de municipios
    public function listaMunicipios()
    {
        return view('admin.brigadistas.lista_municipios');
    }
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
public function asignaciones()
{
    // Obtener únicamente la brigada activa
    $brigada = Brigada::where('estado', 1)->first();

    // Si no hay brigada activa, no mostrar registros
    if (!$brigada) {

        $brigadistas = collect();

    } else {

        // Solo brigadistas registrados y validados
        // en la brigada activa
        $brigadistas = Brigadista::whereHas('registros', function ($q) use ($brigada) {

            $q->where('brigada_id', $brigada->id)
              ->where('status', 'validado');

        })
        ->with([
            'asignaciones' => function ($q) use ($brigada) {

                $q->where('brigada_id', $brigada->id);

            }
        ])
        ->get();
    }

    // Estados
    $estados = EstadoMunicipio::select('estado')
        ->distinct()
        ->orderBy('estado')
        ->get();

    // Proyectos
    $proyectos = [
        'Atención integral a la salud',
        'Levantamiento de poligonales cerradas',
        'Diagnóstico de ordenamiento vial',
        'Proyecto de vialidades (calles y caminos)',
        'Diseño arquitectónico',
        'Instalación de análisis de sistemas de bombeo',
        'Línea de conducción de agua potable',
        'Captación de agua pluvial para sistema de riego',
        'Reconocimiento de plantas endémicas a través del uso de visión artificial',
        'Mantenimiento correctivo y preventivo de sistemas de computo',
        'Diseño de página web',
        'Conservación de conocimientos tradicionales a través de inteligencia artificial',
        'Proyecto de desarrollo turístico',
        'Rescate de archivos históricos municipales',
        'Evaluación diagnostica de servicios públicos bibliotecarios',
        'Disponibilidad y uso de materiales en bibliotecas publicas',
        'Capacitación para la producción de compostas y huertos comunitarios',
        'Proyecto productivo para la elaboración de bebidas y alimentos'
    ];

    return view('admin.asignaciones', compact(
        'brigadistas',
        'estados',
        'proyectos',
        'brigada'
    ));
}
public function guardarFila(Request $request)
{
    $brigadaActiva = Brigada::where('estado', 1)->first();

    $asignacion = Asignacion::updateOrCreate(
        [
            'brigadista_id' => $request->id,
            'brigada_id' => $brigadaActiva->id,
        ],
        [
            'estado_asignado' => $request->estado,
            'municipio_asignado' => $request->municipio,
            'proyecto_asignado' => $request->proyecto,
            'publicado' => false,
        ]
    );

    $this->registrarBitacora(
        'Modificó asignación',
        "Asignó al brigadista el estado {$request->estado}, municipio {$request->municipio} y proyecto {$request->proyecto}.",
        $request->id
    );

    return response()->json([
        'modifico' => $asignacion->modifico
    ]);
}

public function guardarAsignaciones(Request $request)
{
    $brigadaActiva = Brigada::where('estado', 1)->first();

    if (!$brigadaActiva) {
        return redirect()->back()->with(
            'error',
            'No hay una brigada activa para asignar.'
        );
    }

    $municipios = $request->municipio ?? [];

    if (empty($municipios) || !is_array($municipios)) {
        return redirect()->back()->with(
            'error',
            'No se recibieron municipios para asignar.'
        );
    }

    foreach ($request->municipio as $brigadista_id => $municipio) {

        $estado = $request->estado[$brigadista_id] ?? null;
        $proyecto = $request->proyecto[$brigadista_id] ?? null;

        Asignacion::updateOrCreate(
            [
                'brigadista_id' => $brigadista_id,
                'brigada_id' => $brigadaActiva->id,
            ],
            [
                'municipio_asignado' => $municipio,
                'estado_asignado' => $estado,
                'proyecto_asignado' => $proyecto,
                'publicado' => true,
                'modifico' => auth()->user()->name
            ]
        );

        $this->registrarBitacora(
            'Asignó brigadista',
            "Asignó estado {$estado}, municipio {$municipio} y proyecto {$proyecto}.",
            $brigadista_id
        );
    }

    return redirect()->back()->with(
        'success',
        'Asignaciones guardadas correctamente.'
    );
}

public function obtenerMunicipios($estado)
{
    $municipios = \DB::table('estados_municipios')
        ->where('estado', $estado)
        ->orderBy('municipio')
        ->pluck('municipio');

    return response()->json($municipios);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = $request->role;

    // Generar 2FA
    $google2fa = new Google2FA();
    $secretKey = $google2fa->generateSecretKey();
    $user->google2fa_secret = $secretKey;

    $user->save();
    $this->registrarBitacora(
    'Creó usuario',
    "Se creó el usuario {$user->name} con correo {$user->email} y rol {$user->role}."
);

    // URL QR
    $qrCodeUrl = $google2fa->getQRCodeUrl('PLANASSZE', $user->email, $secretKey);

    // Retornar la vista directamente con las variables
    return view('admin.usuarios.crear', [
        'qrCodeUrl' => $qrCodeUrl,
        'secretKey' => $secretKey
    ]);
}

public function update(Request $request, User $usuario)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $usuario->id,
        'role' => 'required|string',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $usuario->name = $request->name;
    $usuario->email = $request->email;
    $usuario->role = $request->role;

    if ($request->password) {
        $usuario->password = bcrypt($request->password);
    }

    // Regenerar 2FA
    $google2fa = new Google2FA();
    $secretKey = $google2fa->generateSecretKey();
    $usuario->google2fa_secret = $secretKey;

    $usuario->save();
    $this->registrarBitacora(
    'Modificó usuario',
    "Se modificó el usuario {$usuario->name} ({$usuario->email})."
);

    // URL QR
    $qrCodeUrl = $google2fa->getQRCodeUrl('PLANASSZE', $usuario->email, $secretKey);

    return redirect()->route('admin.usuarios.editar', $usuario->id)
                     ->with(['qrCodeUrl' => $qrCodeUrl, 'secretKey' => $secretKey]);
}
 
public function enable2fa(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'otp_code' => 'required|string',
    ]);

    $user = User::findOrFail($request->user_id);
    $google2fa = new Google2FA();

    // Verificar OTP
    $valid = $google2fa->verifyKey($user->google2fa_secret, $request->otp_code);

    if ($valid) {
        $user->two_factor_enabled = true; 
        $user->save();

        return redirect()->back()->with('success', 'Autenticación de dos factores habilitada correctamente.');
    } else {
        return redirect()->back()->withErrors(['otp_code' => 'Código incorrecto. Intenta nuevamente.']);
    }
}

public function disable2fa(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $user = User::findOrFail($request->user_id);

    // Deshabilitar 2FA
    $user->two_factor_enabled = false;
    $user->google2fa_secret = null; 
    $user->save();

    return redirect()->back()->with('success', 'Autenticación de dos factores deshabilitada correctamente.');
}

public function registrosCompleto()
{
    // Traemos todos los registros de brigadistas junto con su brigada
    $brigadistas = Brigadista::with(['registros.brigada', 'bitacoras.usuario'])->get();

    // Traemos todas las brigadas, solo para el filtro en la vista
    $brigadas = Brigada::orderBy('año', 'desc')->get();

    return view('admin.registros_completo', compact('brigadistas', 'brigadas'));
}
public function login(Request $request)
{
    $credentials = $request->only('correo', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors(['correo' => 'Credenciales incorrectas']);
}

public function mostrarRestablecerPassword($id)
{
    $brigadista = Brigadista::findOrFail($id);
    return view('admin.restablecer_brigadista', compact('brigadista'));
}
public function restablecerPassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $brigadista = Brigadista::findOrFail($id);

    // Guardar la contraseña
    $brigadista->password = Hash::make($request->password);

    $brigadista->password_real = $request->password;

    $brigadista->save();

    return redirect()->route('admin.lista_brigadistas') 
        ->with('success', 'Contraseña restablecida correctamente.');
}

public function listasAsistencia(Request $request)
{
    $brigadaActiva = Brigada::where('estado', 1)->first();

    if ($brigadaActiva) {

        $brigadistas = Brigadista::whereHas('asignaciones', function($q) use ($brigadaActiva) {
            $q->where('brigada_id', $brigadaActiva->id)
              ->where('publicado', false);
        })
        ->whereHas('registros', function($q) use ($brigadaActiva) {
            $q->where('brigada_id', $brigadaActiva->id)
              ->where('status', 'validado');
        })
        ->with([
            'asignaciones.estadoMunicipio',
            'registros',
            'brigadas',
            'asignaciones.supervisor'
        ])
        ->when($request->municipio, fn($q) =>
            $q->whereHas('asignaciones.estadoMunicipio', fn($m) =>
                $m->where('municipio', 'like', '%'.$request->municipio.'%')
            )
        )
        ->when($request->proyecto, fn($q) =>
            $q->whereHas('asignaciones', fn($a) =>
                $a->where('proyecto_asignado', 'like', '%'.$request->proyecto.'%')
            )
        )
        ->when($request->carrera, fn($q) =>
            $q->where('programa_academico', 'like', '%'.$request->carrera.'%')
        )
        ->when($request->boleta, fn($q) =>
            $q->where('numero_boleta', 'like', '%'.$request->boleta.'%')
        )
        ->when($request->rol, fn($q) =>
            $q->whereHas('registros', fn($r) =>
                $r->whereRaw(
                    'UPPER(rol_deseado) = ?',
                    [strtoupper($request->rol)]
                )
            )
        )
        ->get();

    } else {

        $brigadistas = collect();
    }

    if ($brigadaActiva) {
    $supervisores = Supervisor::where('brigada_id', $brigadaActiva->id)
        ->with([
            'asignaciones' => function ($q) use ($brigadaActiva) {
                $q->where('brigada_id', $brigadaActiva->id);
            }
        ])
        ->orderBy('apellido_paterno')
        ->orderBy('apellido_materno')
        ->orderBy('nombre')
        ->get();
} else {
    $supervisores = collect();
}

    $municipios = EstadoMunicipio::orderBy('municipio')->pluck('municipio');

    $carreras = Brigadista::select('programa_academico')
        ->distinct()
        ->pluck('programa_academico');

    $proyectos = [
        "Atención integral a la salud",
        "Levantamiento de poligonales cerradas",
        "Diagnóstico de ordenamiento vial",
        "Proyecto de vialidades (calles y caminos)",
        "Diseño arquitectónico",
        "Instalación de análisis de sistemas de bombeo",
        "Línea de conducción de agua potable",
        "Captación de agua pluvial para sistema de riego",
        "Reconocimiento de plantas endémicas a través de visión artificial",
        "Mantenimiento correctivo y preventivo de sistemas de cómputo",
        "Diseño de página web",
        "Conservación de conocimientos tradicionales mediante inteligencia artificial",
        "Proyecto de desarrollo turístico",
        "Rescate de archivos históricos municipales",
        "Evaluación diagnóstica de servicios públicos bibliotecarios",
        "Disponibilidad y uso de materiales en bibliotecas públicas",
        "Capacitación para producción de compostas y huertos comunitarios",
        "Proyecto productivo para elaboración de bebidas y alimentos"
    ];

    $roles = ['Brigadista', 'Coordinador', 'Supervisor'];

    return view('admin.lista_asistencia', compact(
        'brigadistas',
        'supervisores',
        'municipios',
        'carreras',
        'proyectos',
        'roles',
        'brigadaActiva'
    ));
}


public function descargarListaPDF(Request $request)
{
    $brigadaActiva = Brigada::where('estado', 1)->first();

    if (!$brigadaActiva) {
        return back()->with('error', 'No hay brigada activa.');
    }


    $supervisores = Supervisor::where('brigada_id', $brigadaActiva->id)
    ->with(['asignaciones' => function ($q) use ($brigadaActiva, $request) {

        $q->where('brigada_id', $brigadaActiva->id);

        if ($request->filled('municipio')) {
            $q->where('municipio_asignado', $request->municipio);
        }

    }])
    ->get()
    ->filter(function ($supervisor) {
        return $supervisor->asignaciones->isNotEmpty();
    })
    ->values();

    $brigadistas = Brigadista::whereHas('asignaciones', function ($q) use ($brigadaActiva, $request) {

        $q->where('brigada_id', $brigadaActiva->id)
          ->where('publicado', false);

        if ($request->filled('municipio')) {
            $q->where('municipio_asignado', $request->municipio);
        }

    })
    ->whereHas('registros', function ($q) use ($brigadaActiva) {

        $q->where('brigada_id', $brigadaActiva->id)
          ->where('status', 'validado');

    })
    ->with([
        'registros' => function ($q) use ($brigadaActiva) {
            $q->where('brigada_id', $brigadaActiva->id)
              ->where('status', 'validado');
        },

        'asignaciones' => function ($q) use ($brigadaActiva, $request) {

            $q->where('brigada_id', $brigadaActiva->id)
              ->where('publicado', false);

            if ($request->filled('municipio')) {
                $q->where('municipio_asignado', $request->municipio);
            }

        },

        'asignaciones.supervisor',
        'asignaciones.estadoMunicipio',
        'brigadas'
    ])
    ->get();

    $brigadistas = $brigadistas->filter(function ($brigadista) {
        return $brigadista->asignaciones->isNotEmpty();
    })->values();


    if ($brigadistas->isEmpty() && $supervisores->isEmpty()) {
        return back()->with(
            'error',
            'No se encontraron registros con los filtros seleccionados.'
        );
    }

   
    $pdf = Pdf::loadView('admin.asistencia_pdf', [
        'brigadistas' => $brigadistas,
        'supervisores' => $supervisores,
        'brigada' => $brigadaActiva
    ])->setPaper('letter', 'landscape');


    return $pdf->stream('Lista_Asistencia.pdf');
}
public function mostrarBitacoras()
{
    $bitacoras = Bitacora::with(['usuario', 'brigadista'])->orderBy('created_at', 'desc')->get();
    return view('admin.bitacoras', compact('bitacoras'));
}

private function registrarBitacora($accion, $observaciones = null, $brigadistaId = null)
{
    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => $brigadistaId,
        'accion' => $accion,
        'observaciones' => $observaciones,
    ]);
}
 public function create()
    {
        // Unidades Académicas
        $unidades = [
            'ESIA_TICOMAN' => 'Escuela Superior de Ingeniería y Arquitectura, Unidad Ticomán',
            'ESIA_ZACATENCO' => 'Escuela Superior de Ingeniería y Arquitectura, Unidad Zacatenco',
            'ESIA_TECAMACHALCO' => 'Escuela Superior de Ingeniería y Arquitectura, Unidad Tecamachalco',
            'ESIME_TICOMAN' => 'Escuela Superior de Ingeniería Mecánica y Eléctrica, Unidad Ticomán',
            'ESIME_ZACATENCO' => 'Escuela Superior de Ingeniería Mecánica y Eléctrica, Unidad Zacatenco',
            'ESIME_CULHUACAN' => 'Escuela Superior de Ingeniería Mecánica y Eléctrica, Unidad Culhuacán',
            'ESIME_AZCAPOTZALCO' => 'Escuela Superior de Ingeniería Mecánica y Eléctrica, Unidad Azcapotzalco',
            'UPIIG' => 'Unidad Profesional Interdisciplinaria de Ingeniería, Campus Guanajuato',
            'UPIIZ' => 'Unidad Profesional Interdisciplinaria de Ingeniería, Campus Zacatecas',
            'UPIBI' => 'Unidad Profesional Interdisciplinaria de Biotecnología',
            'UPIITA' => 'Unidad Profesional Interdisciplinaria en Ingeniería y Tecnologías Avanzadas',
            'UPIIP' => 'Unidad Profesional Interdisciplinaria de Ingeniería, Campus Palenque',
            'UPIIH' => 'Unidad Profesional Interdisciplinaria de Ingeniería, Campus Hidalgo',
            'UPIIT' => 'Unidad Profesional Interdisciplinaria de Ingeniería, Campus Tlaxcala',
            'ESIQIE' => 'Escuela Superior de Ingeniería Química e Industrias Extractivas',
            'ESFM' => 'Escuela Superior de Física y Matemáticas',
            'ESIT' => 'Escuela Superior de Ingeniería Textil',
            'ESCOM' => 'Escuela Superior de Cómputo',
            'UPIICSA' => 'Unidad Profesional Interdisciplinaria de Ingeniería y Ciencias Sociales y Administrativas',
            'UPIEM' => 'Unidad Profesional Interdisciplinaria de Energía y Movilidad',
            'ENCB' => 'Escuela Nacional de Ciencias Biológicas',
            'ESEO' => 'Escuela Superior de Enfermería y Obstetricia',
            'ESM' => 'Escuela Superior de Medicina',
            'ENMYH' => 'Escuela Nacional de Medicina y Homeopatía',
            'CICS_UMA' => 'Centro Interdisciplinario de Ciencias de la Salud, Unidad Milpa Alta',
            'CICS_UST' => 'Centro Interdisciplinario de Ciencias de la Salud, Unidad Santo Tomás',
            'ESCA_UST' => 'Escuela Superior de Comercio y Administración, Unidad Santo Tomás',
            'ESCA_UT' => 'Escuela Superior de Comercio y Administración, Unidad Tepepan',
            'ESE' => 'Escuela Superior de Economía',
            'EST' => 'Escuela Superior de Turismo',
            'ENBA' => 'Escuela Nacional de Biblioteconomía y Archivonomía',
        ];

        // Relación Programas por Unidad
    $programas = [
        'ESIA_TICOMAN' => [
            'INGENIERÍA EN METEOROLOGÍA',
            'INGENIERÍA GEOFÍSICA',
            'INGENIERÍA GEOLÓGICA',
            'INGENIERÍA PETROLERA',
            'INGENIERÍA TOPOGRÁFICA Y FOTOGRAMÉTRICA',
        ],
        'ESIA_ZACATENCO' => ['INGENIERÍA CIVIL'],
        'ESIA_TECAMACHALCO' => ['INGENIERO ARQUITECTO'],
        'ESIME_TICOMAN' => [
            'INGENIERÍA AERONÁUTICA',
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
        ],
        'ESIME_ZACATENCO' => [
            'INGENIERÍA ELÉCTRICA',
            'INGENIERÍA EN COMUNICACIONES Y ELECTRÓNICA',
            'INGENIERÍA EN CONTROL Y AUTOMATIZACIÓN',
            'INGENIERÍA FOTÓNICA',
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
        ],
        'ESIME_CULHUACAN' => [
            'INGENIERÍA EN COMPUTACIÓN',
            'INGENIERÍA EN COMUNICACIONES Y ELECTRÓNICA',
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
            'INGENIERÍA MECÁNICA',
        ],
        'ESIME_AZCAPOTZALCO' => [
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
            'INGENIERÍA MECÁNICA',
            'INGENIERÍA EN ROBÓTICA INDUSTRIAL',
        ],
        'UPIIG' => [
            'INGENIERÍA AERONÁUTICA',
            'INGENIERÍA BIOTECNOLÓGICA',
            'INGENIERÍA FARMACÉUTICA',
            'INGENIERÍA INDUSTRIAL',
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
        ],
        'UPIIZ' => [
            'INGENIERÍA AMBIENTAL',
            'INGENIERÍA EN ALIMENTOS',
            'INGENIERÍA EN INTELIGENCIA ARTIFICIAL',
            'INGENIERÍA EN SISTEMAS COMPUTACIONALES',
            'INGENIERÍA MECATRÓNICA',
            'INGENIERÍA METALÚRGICA',
        ],
        'UPIBI' => [
            'INGENIERÍA AMBIENTAL',
            'INGENIERÍA BIOMÉDICA',
            'INGENIERÍA BIOTECNOLÓGICA',
            'INGENIERÍA EN ALIMENTOS',
            'INGENIERÍA FARMACÉUTICA',
        ],
        'UPIITA' => [
            'INGENIERÍA BIÓNICA',
            'INGENIERÍA EN ENERGÍA',
            'INGENIERÍA MECATRÓNICA',
            'INGENIERÍA TELEMÁTICA',
        ],
        'UPIIP' => [
            'INGENIERÍA BIOTECNOLÓGICA',
            'INGENIERÍA CIVIL',
            'INGENIERÍA FERROVIARIA',
            'LICENCIATURA EN TURISMO SUSTENTABLE',
        ],
        'UPIIH' => [
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
            'INGENIERÍA MECATRÓNICA',
        ],
        'UPIIT' => [
            'INGENIERÍA BIOTECNOLÓGICA',
            'INGENIERÍA EN INTELIGENCIA ARTIFICIAL',
            'INGENIERÍA EN SISTEMAS AUTOMOTRICES',
            'INGENIERÍA EN TRANSPORTE',
            'LICENCIATURA EN CIENCIA DE DATOS',
        ],
        'ESIQIE' => [
            'INGENIERÍA EN METALURGIA Y MATERIALES',
            'INGENIERÍA QUÍMICA INDUSTRIAL',
            'INGENIERÍA QUÍMICA PETROLERA',
        ],
        'ESFM' => [
            'INGENIERÍA MATEMÁTICA',
            'LICENCIATURA EN FÍSICA Y MATEMÁTICAS',
            'LICENCIATURA EN MATEMÁTICA ALGORÍTMICA',
        ],
        'ESIT' => ['INGENIERÍA TEXTIL'],
        'ESCOM' => [
            'INGENIERÍA EN INTELIGENCIA ARTIFICIAL',
            'INGENIERÍA EN SISTEMAS COMPUTACIONALES',
            'LICENCIATURA EN CIENCIA DE DATOS',
        ],
        'UPIICSA' => [
            'INGENIERÍA EN INFORMÁTICA',
            'INGENIERÍA EN TRANSPORTE',
            'INGENIERÍA FERROVIARIA',
            'INGENIERÍA INDUSTRIAL',
            'LICENCIATURA EN CIENCIAS DE LA INFORMÁTICA',
            'LICENCIATURA EN ADMINISTRACIÓN INDUSTRIAL',
        ],
        'UPIEM' => [
            'INGENIERÍA EN MOVILIDAD URBANA',
            'INGENIERÍA EN NEGOCIOS ENERGÉTICOS SUSTENTABLES',
            'INGENIERÍA EN SISTEMAS ENERGÉTICOS Y REDES INTELIGENTES',
        ],
        'ENCB' => [
            'INGENIERÍA BIOQUÍMICA',
            'INGENIERÍA EN SISTEMAS AMBIENTALES',
            'LICENCIATURA EN BIOLOGÍA',
            'QUÍMICO BACTERIÓLOGO Y PARASITÓLOGO',
            'QUÍMICO FARMACÉUTICO INDUSTRIAL',
        ],
        'ESEO' => [
            'LICENCIATURA EN ENFERMERÍA',
            'LICENCIATURA EN ENFERMERÍA Y OBSTETRICIA',
        ],
        'ESM' => ['MÉDICO CIRUJANO PARTERO'],
        'ENMYH' => [
            'MÉDICO CIRUJANO HOMEÓPATA',
            'MÉDICO CIRUJANO PARTERO',
        ],
        'CICS_UMA' => [
            'LICENCIATURA EN ENFERMERÍA',
            'LICENCIATURA EN NUTRICIÓN',
            'LICENCIATURA EN ODONTOLOGÍA',
            'LICENCIATURA EN OPTOMETRÍA',
            'LICENCIATURA EN TRABAJO SOCIAL',
            'MÉDICO CIRUJANO PARTERO',
        ],
        'CICS_UST' => [
            'LICENCIATURA EN ODONTOLOGÍA',
            'LICENCIATURA EN OPTOMETRÍA',
            'LICENCIATURA EN PSICOLOGÍA',
        ],
        'ESCA_UST' => [
            'CONTADOR PÚBLICO',
            'LICENCIATURA EN ADMINISTRACIÓN Y DESARROLLO EMPRESARIAL',
            'LICENCIATURA EN MERCADOTECNIA DIGITAL',
            'LICENCIATURA EN NEGOCIOS DIGITALES',
            'LICENCIATURA EN NEGOCIOS INTERNACIONALES',
            'LICENCIATURA EN RELACIONES COMERCIALES',
            'LICENCIATURA EN COMERCIO INTERNACIONAL',
        ],
        'ESCA_UT' => [
            'CONTADOR PÚBLICO',
            'LICENCIATURA EN NEGOCIOS INTERNACIONALES',
            'LICENCIATURA EN RELACIONES COMERCIALES',
            'LICENCIATURA EN CONTADURÍA Y FINANZAS PÚBLICAS',
        ],
        'ESE' => ['LICENCIATURA EN ECONOMÍA'],
        'EST' => ['LICENCIATURA EN TURISMO'],
        'ENBA' => [
            'LICENCIATURA EN ARCHIVONOMÍA',
            'LICENCIATURA EN BIBLIOTECONOMÍA',
        ],
    ];
        return view('admin.registrar_brigadista', compact('unidades', 'programas'));
    }

    // Guardar alumno/brigadista
    public function storeBrigadista(Request $request)
    {
        $request->validate([
            'numero_boleta' => 'required|alpha_num|unique:brigadistas,numero_boleta',
            'apellido_paterno' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'apellido_materno' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'nombre' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'curp' => 'required|alpha_num|size:18',
            'genero' => 'required|in:MASCULINO,FEMENINO,OTRO',
            'edad' => 'required|numeric|min:1|max:120',
            'telefono' => 'required|numeric',
            'correo' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'unidad_academica' => 'required',
            'programa_academico' => 'required',
            'institucion_ss' => 'required|in:IMSS,ISSSTE,BIENESTAR,PRIVADO',
            'nss' => 'required|alpha_num',
            'clabe' => 'required|numeric|digits:18',
            'banco' => 'required|in:BBVA,SANTANDER,BANORTE,HSBC,BANCO AZTECA,BANCOPPEL',
            'foto' => 'required|mimes:jpg,jpeg,png|max:2048',

            'calle' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
            'no_ext' => 'required|regex:/^[A-Z0-9]+$/i',
            'colonia' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
            'municipio' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'estado' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'cp' => 'required|numeric|digits:5',

            'contacto_nombre' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'contacto_parentesco' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'calle_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
            'no_ext_contacto' => 'required|regex:/^[A-Z0-9]+$/i',
            'colonia_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
            'municipio_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÜÑ0-9\s\-\.,]+$/iu',
            'estado_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'cp_contacto' => 'required|numeric|digits:5',
            'telefono_contacto' => 'required|numeric',

            'no_int' => 'nullable|regex:/^[A-Z0-9]+$/i',
            'especialidad' => 'nullable|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'municipio_anterior' => 'nullable|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
            'no_int_contacto' => 'nullable|regex:/^[A-Z0-9]+$/i',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['password_real'] = $request->password;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        // Convertir campos a mayúsculas
        $uppercaseFields = [
            'apellido_paterno', 'apellido_materno', 'nombre', 'especialidad',
            'municipio_anterior', 'calle', 'colonia', 'municipio', 'estado',
            'contacto_nombre', 'contacto_parentesco', 'calle_contacto', 'colonia_contacto',
            'municipio_contacto', 'estado_contacto'
        ];

        foreach ($uppercaseFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = mb_strtoupper($data[$field], 'UTF-8');
            }
        }

        $brigadista = Brigadista::create($data);

Bitacora::create([
    'usuario_id' => Auth::id(),
    'brigadista_id' => $brigadista->id,
    'accion' => 'Registró brigadista',
    'observaciones' => 'Se registró un nuevo brigadista en el sistema.',
]);

return redirect()->back()->with('success', 'Brigadista registrado correctamente.');
    }

    // Mostrar formulario
        public function registrarSupervisor()
       { 
         return view('admin.registrar_supervisor');
       }

        // Guardar supervisor
public function guardarSupervisor(Request $request)
{
    // Obtener brigada activa
    $brigadaActiva = Brigada::where('estado', 1)->first();

    if (!$brigadaActiva) {
        return redirect()->back()->with(
            'error',
            'No hay una brigada activa. No se puede registrar supervisor.'
        );
    }

    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'required|string|max:255',
        'curp' => 'required|string|max:18|unique:supervisores,curp',
        'correo' => 'required|email|unique:supervisores,correo',
        'telefono' => 'required|string|max:20',
        'clabe' => 'required|string|max:18',
        'banco' => 'required|string|max:100',
    ]);

    $supervisor = Supervisor::create([
        'nombre' => $request->nombre,
        'apellido_paterno' => $request->apellido_paterno,
        'apellido_materno' => $request->apellido_materno,
        'curp' => $request->curp,
        'correo' => $request->correo,
        'telefono' => $request->telefono,
        'clabe' => $request->clabe,
        'banco' => $request->banco,
        'brigada_id' => $brigadaActiva->id,
    ]);

    // Registrar acción en bitácora
    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Registró supervisor',
        'observaciones' => 'Registró al supervisor '
            . $supervisor->nombre . ' '
            . $supervisor->apellido_paterno . ' '
            . $supervisor->apellido_materno
            . ' para la brigada '
            . $brigadaActiva->brigada . ' '
            . $brigadaActiva->año . '.',
    ]);

    return redirect()
        ->back()
        ->with('success', 'Supervisor registrado correctamente.');
}

public function editarSupervisor($id)
{
    $supervisor = Supervisor::findOrFail($id);

    return view('admin.editar_supervisor', compact('supervisor'));
}

public function actualizarSupervisor(Request $request, $id)
{
    $supervisor = Supervisor::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido_paterno' => 'required|string|max:255',
        'apellido_materno' => 'required|string|max:255',
        'curp' => 'required|string|max:18',
        'correo' => 'required|email',
        'telefono' => 'required|string|max:20',
        'clabe' => 'required|string|max:18',
        'banco' => 'required|string|max:100',
    ]);

    $supervisor->update([
        'nombre' => $request->nombre,
        'apellido_paterno' => $request->apellido_paterno,
        'apellido_materno' => $request->apellido_materno,
        'curp' => $request->curp,
        'correo' => $request->correo,
        'telefono' => $request->telefono,
        'clabe' => $request->clabe,
        'banco' => $request->banco,
    ]);

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Actualizó supervisor',
        'observaciones' => 'Actualizó los datos del supervisor '
            . $supervisor->nombre . ' '
            . $supervisor->apellido_paterno . ' '
            . $supervisor->apellido_materno
            . ' (ID: ' . $supervisor->id . ').',
    ]);

    return redirect()
        ->route('admin.lista_supervisores')
        ->with('success', 'Supervisor actualizado correctamente.');
}
public function eliminarSupervisor($id)
{
    $supervisor = Supervisor::findOrFail($id);

    $nombreSupervisor = $supervisor->nombre . ' '
        . $supervisor->apellido_paterno . ' '
        . $supervisor->apellido_materno;

    $supervisorId = $supervisor->id;

    $supervisor->delete();

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => 'Eliminó supervisor',
        'observaciones' => 'Eliminó al supervisor '
            . $nombreSupervisor
            . ' (ID: ' . $supervisorId . ').',
    ]);

    return redirect()
        ->route('admin.lista_supervisores')
        ->with('success', 'Supervisor eliminado correctamente.');
}

public function listaSupervisores()
{
    // Obtener la brigada activa
    $brigadaActiva = Brigada::where('estado', 1)->first();

    // Si no hay brigada activa, no mostrar supervisores
    if (!$brigadaActiva) {
        $supervisores = collect();

        return view('Admin.lista_supervisores', compact(
            'supervisores',
            'brigadaActiva'
        ));
    }

    // Mostrar únicamente supervisores registrados en la brigada activa
    $supervisores = Supervisor::where('brigada_id', $brigadaActiva->id)
        ->orderBy('apellido_paterno')
        ->orderBy('apellido_materno')
        ->orderBy('nombre')
        ->get();

    return view('Admin.lista_supervisores', compact(
        'supervisores',
        'brigadaActiva'
    ));
}


public function asignacionSupervisores()
{
    // Obtener brigada activa
    $brigadaActiva = Brigada::where('estado', 1)->first();

    // Obtener estados
    $estados = EstadoMunicipio::select('estado')
        ->distinct()
        ->orderBy('estado')
        ->get();

    // Si no hay brigada activa
    if (!$brigadaActiva) {
        return view(
            'Admin.asignacion_supervisores',
            [
                'supervisores' => collect(),
                'estados' => $estados,
                'brigadaActiva' => null
            ]
        );
    }

    // Supervisores pertenecientes únicamente a la brigada activa
    $supervisores = Supervisor::where(
        'brigada_id',
        $brigadaActiva->id
    )
    ->with([
        'asignaciones' => function ($query) use ($brigadaActiva) {
            $query->where('brigada_id', $brigadaActiva->id);
        }
    ])
    ->orderBy('apellido_paterno')
    ->orderBy('apellido_materno')
    ->orderBy('nombre')
    ->get();

    return view(
        'Admin.asignacion_supervisores',
        compact(
            'supervisores',
            'estados',
            'brigadaActiva'
        )
    );
}

// Guardar fila individual vía AJAX
public function guardarAsignacionSupervisorFila(Request $request)
{
    $brigadaActiva = Brigada::where('estado', 1)->first();

    if (!$brigadaActiva) {
        return response()->json([
            'error' => 'No hay brigada activa.'
        ], 400);
    }

    $supervisor = Supervisor::where('id', $request->id)
        ->where('brigada_id', $brigadaActiva->id)
        ->first();

    if (!$supervisor) {
        return response()->json([
            'error' => 'El supervisor no pertenece a la brigada activa.'
        ], 403);
    }

    $asignacionExistente = Asignacion::where('supervisor_id', $supervisor->id)
        ->where('brigada_id', $brigadaActiva->id)
        ->first();

    $accion = $asignacionExistente
        ? 'Modificó asignación de supervisor'
        : 'Asignó supervisor';

    $asignacion = Asignacion::updateOrCreate(
        [
            'supervisor_id' => $supervisor->id,
            'brigada_id' => $brigadaActiva->id,
        ],
        [
            'estado_asignado' => $request->estado,
            'municipio_asignado' => $request->municipio,
            'publicado' => false,
        ]
    );

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => null,
        'accion' => $accion,
        'observaciones' => 'Supervisor: '
            . $supervisor->nombre . ' '
            . $supervisor->apellido_paterno . ' '
            . $supervisor->apellido_materno
            . '. Estado: ' . $request->estado
            . '. Municipio: ' . $request->municipio
            . '. Brigada: ' . $brigadaActiva->brigada
            . ' (' . $brigadaActiva->año . ').',
    ]);

    return response()->json([
        'modifico' => $asignacion->modifico
    ]);
}

public function credenciales(Request $request)
{
    $brigadistas = Brigadista::with([
        'registros.brigada',
        'bitacoras.usuario',
        'asignacion'
    ])
    ->whereHas('registros.brigada', function ($query) {
        $query->where('estado', 1);
    })
    ->whereHas('asignacion', function ($query) {
        $query->whereNotNull('brigada_id');
    });

    if ($request->filled('boleta')) {
        $brigadistas->where(
            'numero_boleta',
            'like',
            '%' . $request->boleta . '%'
        );
    }

    if ($request->filled('folio')) {
        $brigadistas->where(
            'id',
            'like',
            '%' . $request->folio . '%'
        );
    }

    if ($request->filled('municipio')) {
        $brigadistas->whereHas('asignacion', function ($query) use ($request) {
            $query->where(
                'municipio_asignado',
                'like',
                '%' . $request->municipio . '%'
            );
        });
    }

    $brigadistas = $brigadistas->get();

    $municipios = Asignacion::whereNotNull('municipio_asignado')
        ->where('municipio_asignado', '!=', '')
        ->select('municipio_asignado')
        ->distinct()
        ->orderBy('municipio_asignado')
        ->pluck('municipio_asignado');

    $brigadas = Brigada::where('estado', 1)
        ->orderBy('año', 'desc')
        ->get();

    return view('Admin.credenciales', compact(
        'brigadistas',
        'brigadas',
        'municipios'
    ));
}

public function credencialPdf($id)
{
    $brigadista = Brigadista::with('registros.brigada')->findOrFail($id);

    $pdf = PDF::loadView('admin.credencial_pdf', compact('brigadista'))
          ->setPaper('letter', 'portrait');


    return $pdf->stream('credencial_'.$brigadista->numero_boleta.'.pdf');
}

public function descargarCredencialesSeleccionadas(Request $request)
{
    $ids = array_filter(explode(',', $request->ids));

    $brigadistas = Brigadista::whereIn('id', $ids)->get();

    if ($brigadistas->isEmpty()) {
        return back()->with('error', 'No se encontraron las credenciales seleccionadas.');
    }

    $pdf = Pdf::loadView('admin.credenciales_multiples', [
        'brigadistas' => $brigadistas
    ])->setPaper('letter', 'portrait');

    return $pdf->download('Credenciales.pdf');
}

}

