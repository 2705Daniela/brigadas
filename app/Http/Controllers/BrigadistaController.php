<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brigadista;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\PublicKeyLoader;
use App\Models\Brigada;
use App\Models\Registro;
use App\Models\CodigoPostal;
use App\Models\Bitacora;

class BrigadistaController extends Controller
{
    
    public function landing()
    {
        return view('brigadistas.landing');
    }

    // Muestra el formulario de registro del brigadista
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

    return view('brigadistas.create', compact('unidades', 'programas'));
}

public function store(Request $request)
{
    $request->validate([
        // Obligatorios
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
        'foto' => 'required|mimes:jpg,jpeg,png|max:1048',



        // Domicilio obligatorio
        'calle' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
        'no_ext' => 'required|regex:/^[A-Z0-9]+$/i',
        'colonia' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
        'municipio' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'estado' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'cp' => 'required|numeric|digits:5',

        // Contacto emergencia obligatorio
        'contacto_nombre' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'contacto_parentesco' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'calle_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
        'no_ext_contacto' => 'required|regex:/^[A-Z0-9]+$/i',
        'colonia_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ0-9\s]+$/i',
        'municipio_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÜÑ0-9\s\-\.,]+$/iu',
        'estado_contacto' => 'required|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'cp_contacto' => 'required|numeric|digits:5',
        'telefono_contacto' => 'required|numeric',

        // Opcionales
        'no_int' => 'nullable|regex:/^[A-Z0-9]+$/i',
        'especialidad' => 'nullable|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'municipio_anterior' => 'nullable|regex:/^[A-ZÁÉÍÓÚÑ\s]+$/i',
        'no_int_contacto' => 'nullable|regex:/^[A-Z0-9]+$/i',
    ], [
        // Mensajes de validaciones incorrectas
        'numero_boleta.required' => 'Boleta obligatoria',
        'numero_boleta.alpha_num' => 'Boleta inválida',
        'numero_boleta.unique' => 'Boleta ya registrada',
        'apellido_paterno.required' => 'Apellido Paterno obligatorio',
        'apellido_paterno.regex' => 'Apellido Paterno inválido',
        'apellido_materno.required' => 'Apellido Materno obligatorio',
        'apellido_materno.regex' => 'Apellido Materno inválido',
        'nombre.required' => 'Nombre obligatorio',
        'nombre.regex' => 'Nombre inválido',
        'curp.required' => 'CURP obligatoria',
        'curp.alpha_num' => 'CURP inválida',
        'curp.size' => 'CURP debe tener 18 caracteres',
        'genero.required' => 'Género obligatorio',
        'genero.in' => 'Género inválido',
        'edad.required' => 'Edad obligatoria',
        'edad.numeric' => 'Edad inválida',
        'edad.min' => 'Edad mínima 1',
        'edad.max' => 'Edad máxima 120',
        'telefono.required' => 'Teléfono obligatorio',
        'telefono.numeric' => 'Teléfono inválido',
        'correo.required' => 'Correo obligatorio',
        'correo.email' => 'Correo inválido',
        'password.required' => 'Contraseña obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'La confirmación de contraseña no coincide',
        'unidad_academica.required' => 'Unidad obligatoria',
        'programa_academico.required' => 'Programa obligatorio',
        'institucion_ss.required' => 'Institución obligatoria',
        'institucion_ss.in' => 'Institución inválida',
        'nss.required' => 'NSS obligatorio',
        'clabe.required' => 'CLABE obligatoria',
        'clabe.numeric' => 'CLABE inválida',
        'clabe.digits' => 'CLABE debe tener 18 dígitos',
        'banco.required' => 'Banco obligatorio',
        'banco.in' => 'Banco inválido',
        'foto.required' => 'Foto obligatoria',
        'foto.image' => 'Archivo no es imagen',
        'foto.mimes' => 'Solo JPG/PNG',
        'foto.max' => 'Máx 2MB',
        'calle.required' => 'Calle obligatoria',
        'no_ext.required' => 'No. Ext. obligatorio',
        'colonia.required' => 'Colonia obligatoria',
        'municipio.required' => 'Municipio obligatorio',
        'estado.required' => 'Estado obligatorio',
        'cp.required' => 'C.P. obligatorio',
        'cp.numeric' => 'C.P. inválido',
        'cp.digits' => 'C.P. debe tener 5 dígitos',
        'contacto_nombre.required' => 'Contacto obligatorio',
        'contacto_parentesco.required' => 'Parentesco obligatorio',
        'calle_contacto.required' => 'Calle contacto obligatoria',
        'no_ext_contacto.required' => 'No. Ext. contacto obligatorio',
        'colonia_contacto.required' => 'Colonia contacto obligatoria',
        'municipio_contacto.required' => 'Municipio contacto obligatorio',
        'estado_contacto.required' => 'Estado contacto obligatorio',
        'cp_contacto.required' => 'C.P. contacto obligatorio',
        'telefono_contacto.required' => 'Teléfono contacto obligatorio',
    ]);

    $data = $request->all();
    $data['password'] = bcrypt($data['password']);

    $data['password_real'] = $request->password;

    // Guardar foto 
    if ($request->hasFile('foto')) {
        $data['foto'] = $request->file('foto')->store('fotos', 'public');
    }

    // Convertir a mayúsculas los campos de texto
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

     return redirect()->back()->with('success', 'Brigadista creado correctamente, inicia sesión');
}


public function acciones($id)
{
     // Verificar que el brigadista esté logueado
    if (!Auth::guard('brigadista')->check()) {
        return redirect()->route('brigadista.login')
            ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
    }
    // Traer brigadista con su historial de registros y la brigada relacionada
    $brigadista = Brigadista::with('registros.brigada')->findOrFail($id);

    // Traer solo brigadas activas
    $brigadas = Brigada::where('estado', 1)
                        ->orderBy('año', 'desc')
                        ->get();

    // Pasar ambas variables a la vista
    return view('brigadistas.acciones', compact('brigadista', 'brigadas'));
}

public function firma($brigadista, $documento)
{
    $brigadista = Brigadista::findOrFail($brigadista);
    return view('brigadistas.firma', compact('brigadista', 'documento'));
}


public function firmarPDF(Request $request, $brigadistaId)
{
    $brigadista = Brigadista::findOrFail($brigadistaId);

    $request->validate([
        'tipo_firma' => 'required|in:efirma,curp',
        'firma_curp' => 'required_if:tipo_firma,curp',
        'cer'        => 'required_if:tipo_firma,efirma|file',
        'key'        => 'required_if:tipo_firma,efirma|file',
        'password'   => 'required_if:tipo_firma,efirma',
    ]);

    try {
        $dataToSign = "Brigadista: {$brigadista->nombre} {$brigadista->apellido_paterno} {$brigadista->apellido_materno} | Boleta: {$brigadista->numero_boleta}";

        if ($request->tipo_firma === 'efirma') {
            $keyPath = $request->file('key')->getPathname();
            $privateKey = PublicKeyLoader::loadPrivateKey(file_get_contents($keyPath), $request->password);
            $signature = $privateKey->sign($dataToSign);
            $firma_texto  = base64_encode($signature);
        } else {
            $firma_texto  = hash('sha256', $request->firma_curp . $brigadista->numero_boleta . $brigadista->id);
        }

        // Guardar en base de datos
        $brigadista->firma = $firma_texto;
        $brigadista->tipo_firma = $request->tipo_firma; 
        $brigadista->save();

        // Documentos a generar
        $documentos = [
            'ficha'  => 'brigadistas.ficha',
            'carta'  => 'brigadistas.carta_compromiso',
            'status' => 'brigadistas.status_pdf'
        ];

        foreach ($documentos as $doc => $view) {
            $viewData = [
                'brigadista'   => $brigadista,
                'firma_texto'  => $firma_texto,
                'fecha'        => $doc === 'carta' ? strftime('%d de %B de %Y') : ''
            ];

            $pdf = Pdf::loadView($view, $viewData);
            $pdfPath = storage_path("app/public/documentos/{$doc}_firmado_{$brigadista->id}.pdf");
            $pdf->save($pdfPath);
        }

        return redirect()->route('brigadistas.acciones', ['id' => $brigadista->id])
            ->with('success', "Documentos firmados correctamente con {$brigadista->tipo_firma}.");

    } catch (\Exception $e) {
        return back()->with('error', 'Ocurrió un error al firmar los documentos: ' . $e->getMessage());
    }
}

public function generarCarta(Request $request)
{
    $brigadista = Brigadista::findOrFail($request->brigadista_id);

    // Fecha en español
    setlocale(LC_TIME, 'es_ES.UTF-8'); // en Linux/Mac
    $fecha = strftime('%d de %B de %Y'); 

    // Nombre completo
    $brigadista->nombre_completo = $brigadista->nombre . ' ' . $brigadista->apellido_paterno . ' ' . $brigadista->apellido_materno;

    // Determinar firma
    $firma = '';
    if($request->tipo_firma === 'efirma') {
        $firma = 'Firmado con e.Firma';
    } elseif($request->tipo_firma === 'curp') {
        $firma = $request->firma_curp;
    }

    // Variables para la carta
    $brigadista->escuela = $brigadista->unidad_academica ?? '';
    $brigadista->carrera = $brigadista->programa_academico ?? '';
    $brigadista->boleta = $brigadista->numero_boleta ?? '';

    $pdf = \PDF::loadView('brigadistas.carta_compromiso', compact('brigadista', 'fecha', 'firma'));
    return $pdf->stream('Carta_'.$brigadista->nombre_completo.'.pdf');
}
public function mostrarStatusSalud($id)
{
    $brigadista = Brigadista::findOrFail($id);
    return view('brigadistas.status_salud', compact('brigadista'));
}


public function descargarFicha($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = \PDF::loadView('brigadistas.ficha', compact('brigadista', 'firma_texto', 'metodo_firma'));
    return $pdf->download('Ficha_'.$brigadista->nombre.'.pdf');
}

public function descargarCarta($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = \PDF::loadView('brigadistas.carta_compromiso', compact('brigadista', 'firma_texto', 'metodo_firma'));
    return $pdf->download('Carta_'.$brigadista->nombre.'.pdf');
}

public function descargarStatus($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = \PDF::loadView('brigadistas.status_pdf', compact('brigadista', 'firma_texto', 'metodo_firma'));
    return $pdf->download('Status_'.$brigadista->nombre.'.pdf');
}

public function verFicha($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = Pdf::loadView('brigadistas.ficha', compact('brigadista', 'firma_texto', 'metodo_firma'));

    return $pdf->stream('Ficha_Brigadista_'.$brigadista->numero_boleta.'.pdf');
}

public function verStatus($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = \PDF::loadView('brigadistas.status_pdf', compact('brigadista', 'firma_texto', 'metodo_firma'));

    return $pdf->stream('Status_'.$brigadista->numero_boleta.'.pdf');
}

public function verCarta($brigadistaId)
{
    $brigadista = Brigadista::findOrFail($brigadistaId);

    $fecha = Carbon::now()->locale('es')->translatedFormat('j \d\e F \d\e Y');

    // Firma: usar la firma que ya está guardada en BD
    $firma_texto = $brigadista->firma ?? 'Sin firma';
    $metodo_firma = $brigadista->firma ? 
                    (str_contains($firma_texto, 'e.Firma') ? 'Firmado con e.Firma' : 'Firmado con CURP') 
                    : 'Firma';

    $pdf = Pdf::loadView('brigadistas.carta_compromiso', compact('brigadista', 'fecha', 'firma_texto', 'metodo_firma'));

    return $pdf->stream('carta_compromiso_' . $brigadista->numero_boleta . '.pdf');
}


public function gestionar()
{
    $user = Auth::user();

    // Obtener brigadista 
    $brigadista = Brigadista::with('bitacoras.usuario')
        ->where('user_id', $user->id)
        ->firstOrFail();

    return view('brigadistas.gestionar', compact('brigadista'));
}
public function mostrarValidar($id)
{
    $brigadista = Brigadista::with(['registros.brigada'])->findOrFail($id);
    return view('admin.validar', compact('brigadista'));
}

public function validarSubmit($registro_id)
{
    $registro = Registro::findOrFail($registro_id);

    $registro->status = 'validado';
    $registro->comentario = null;
    $registro->save();

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => $registro->brigadista_id,
        'accion' => 'Validó registro',
        'observaciones' => 'El registro fue validado correctamente.',
    ]);

    return redirect()
        ->route('admin.gestionar')
        ->with('success', 'Registro validado correctamente.');
}
public function rechazar(Request $request, $registro_id)
{
    $request->validate([
        'observaciones' => 'required|string|max:500'
    ]);

    $registro = Registro::findOrFail($registro_id);

    $registro->status = 'rechazado';
    $registro->comentario = $request->observaciones;
    $registro->save();

    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => $registro->brigadista_id,
        'accion' => 'Rechazó registro',
        'observaciones' => $request->observaciones,
    ]);

    return redirect()
        ->route('admin.gestionar')
        ->with('success', 'Registro rechazado correctamente.');
}

public function cancelar(Request $request, $registro_id)
{
    $request->validate([
        'observaciones' => 'required|string|max:500'
    ]);

    $registro = Registro::findOrFail($registro_id);

    $registro->status = 'cancelado';
    $registro->comentario = $request->observaciones;
    $registro->save();

    // Registrar acción en bitácora
    Bitacora::create([
        'usuario_id' => Auth::id(),
        'brigadista_id' => $registro->brigadista_id,
        'accion' => 'Canceló registro',
        'observaciones' => $request->observaciones,
    ]);

    return redirect()
        ->route('admin.gestionar')
        ->with('success', 'Registro cancelado correctamente.');
}

public function accionesMasivas(Request $request)
{
    $request->validate([
        'registros' => 'required|array',
        'registros.*' => 'integer|exists:registros,id',
        'accion' => 'required|string|in:validado,rechazado,cancelado'
    ]);

    $brigadaActiva = Brigada::where('estado', 1)->firstOrFail();

    $registros = Registro::whereIn('id', $request->registros)
        ->where('brigada_id', $brigadaActiva->id)
        ->get();

    $usuarioId = Auth::id();

    foreach ($registros as $registro) {

        $registro->status = $request->accion;

        if ($request->accion === 'validado') {
            $registro->comentario = null;
        }

        $registro->save();

        Bitacora::create([
            'usuario_id' => $usuarioId,
            'brigadista_id' => $registro->brigadista_id,
            'accion' => ucfirst($request->accion) . ' registro',
            'observaciones' => 'El registro fue ' . $request->accion . ' por el usuario del sistema.',
        ]);
    }

    $mensajes = [
        'validado' => 'Registros validados correctamente.',
        'rechazado' => 'Registros rechazados correctamente.',
        'cancelado' => 'Registros cancelados correctamente.',
    ];

    return redirect()
        ->back()
        ->with('success', $mensajes[$request->accion]);
}

public function showLogin()
    {
        return view('brigadistas.login_brigadista');
    }
    public function login(Request $request)
{
    $request->validate([
        'correo' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('brigadista')->attempt(['correo' => $request->correo, 'password' => $request->password])) {
        // Login correcto
        return redirect()->route('brigadistas.acciones', ['id' => Auth::guard('brigadista')->id()]);
    }

    return back()->withErrors(['correo' => 'Correo o contraseña incorrectos']);
}

public function logout(Request $request)
{
    Auth::guard('brigadista')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('brigadista.login');
}

    public function registrarBrigada(Request $request)
{
    $request->validate([
        'brigadista_id' => 'required|exists:brigadistas,id',
        'brigada_id' => 'required|exists:brigadas,id',
    ]);

    $brigadista = Brigadista::findOrFail($request->brigadista_id);
    $brigadista->brigada_id = $request->brigada_id;
    $brigadista->save();

    return redirect()->back()->with('success', 'Brigadista registrado en la brigada correctamente.');
}
 

    
 public function actualizarRegistro(Request $request, $id)
{
    $request->validate([
        'brigada_id' => 'required|exists:brigadas,id',
        'rol_deseado' => 'nullable|string|in:BRIGADISTA,COORDINADOR,SUPERVISOR',
        'modalidad' => 'nullable|string|in:VOLUNTARIADO,SERVICIO SOCIAL,HORAS ELECTIVAS,RETRIBUCION SOCIAL,UNIDADES DE PROMOCION (U.P.)',
        'talla' => 'nullable|string|in:S,M,L,XL,XXL',
        'siss' => 'nullable|string|in:SÍ,NO,NO APLICA',
        'participacion_anterior' => 'nullable|string|in:1,2,3 O MÁS,NINGUNA',
        'municipio_anterior' => 'nullable|string|max:100',
    ]);

    $brigadista = Brigadista::findOrFail($id);

    // Verificar que la brigada esté activa
    $brigada = Brigada::where('id', $request->brigada_id)
                       ->where('estado', 1)
                       ->first();

    if (!$brigada) {
        return redirect()->back()->with('error', 'La brigada seleccionada no está activa.');
    }

    $existe = Registro::where('brigadista_id', $brigadista->id)
                      ->where('brigada_id', $brigada->id)
                      ->exists();

    if ($existe) {
        return redirect()->back()->with('error', 'Ya estás registrado en esta brigada.');
    }

 
    Registro::create([
        'brigadista_id' => $brigadista->id,
        'brigada_id' => $brigada->id,
        'rol_deseado' => $request->rol_deseado,
        'modalidad' => $request->modalidad,
        'talla' => $request->talla,
        'siss' => $request->siss,
        'participacion_anterior' => $request->participacion_anterior,
        'municipio_anterior' => $request->municipio_anterior,
    ]);

    return redirect()->back()->with('success', 'Registro de brigada guardado correctamente.');
}

public function eliminarRegistro($id)
{
    $registro = Registro::findOrFail($id);

    
    if ($registro->brigadista_id !== auth()->id()) {
        abort(403, 'No autorizado.');
    }

    $registro->delete();

    return redirect()->back()->with('success', 'Registro eliminado correctamente.');
}

public function verificarPassword(Request $request)
{
    $request->validate([
        'admin_password' => 'required|string',
        'brigadista_id' => 'required|exists:brigadistas,id',
    ]);

    $admin = auth()->user();
    if (!Hash::check($request->admin_password, $admin->password)) {
        return response()->json(['success' => false, 'message' => 'Contraseña incorrecta']);
   }

    $brigadista = Brigadista::find($request->brigadista_id);

    return response()->json([
        'success' => true,
        'password' => $brigadista->password_real,
    ]);
}
public function lista()
{
    $brigadistas = Brigadista::orderBy('id', 'desc')->paginate(20);
    return view('admin.lista_brigadistas', compact('brigadistas')); 

}

public function buscarCP(Request $request)
{
    $cp = $request->query('cp');
    if (!$cp) {
        return response()->json(['error' => 'No se proporcionó el código postal.']);
    }

    $codigos = CodigoPostal::where('codigo', $cp)->get();

    if ($codigos->isEmpty()) {
        return response()->json(['error' => 'Código postal no encontrado.']);
    }

    $estado = $codigos->first()->estado ?? '';
    $municipio = $codigos->first()->municipio ?? '';
    $colonias = $codigos->pluck('asentamiento')->unique()->values();

    return response()->json([
        'estado' => $estado,
        'municipio' => $municipio,
        'colonias' => $colonias
    ]);
}

public function credencialDigital($id)
{
    $brigadista = Brigadista::findOrFail($id);
    return view('brigadistas.credencial-digital', compact('brigadista'));
}

public function credencialPdf($id)
{
    $brigadista = Brigadista::findOrFail($id);

    $pdf = \PDF::loadView('brigadistas.credencial', compact('brigadista'))
        ->setPaper([0, 0, 255, 369]);

    return $pdf->download('credencial.pdf');
}

}


