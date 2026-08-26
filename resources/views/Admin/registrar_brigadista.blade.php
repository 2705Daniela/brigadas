@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="card shadow mb-5">
    <div class="card-header text-white" style="background-color:#6A1B4D;">
       <strong>REGISTRO DEL BRIGADISTA</strong> 
    </div>
    <div class="card-body">
    
            <form id="form-brigadista" action="{{ route('admin.brigadistas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- DATOS DEL BRIGADISTA --}}
                <h5 class="mb-3">DATOS DEL BRIGADISTA</h5>
                <div class="row mb-3">
                <div class="col-md-4">
                     <label>Número de boleta o empleado</label>
                <div class="input-group">
                     <input type="text" name="numero_boleta" id="numero_boleta" class="form-control" value="{{ old('numero_boleta') }}">
                     <button type="button" id="btnBuscarAlumno" class="btn btn-primary">Buscar</button>
                    <button type="button" id="btnLimpiarFormulario" class="btn btn-secondary">Limpiar</button>
                </div>
                <span class="text-danger error-text numero_boleta_error"></span>
                </div>
             <div id="resultado-busqueda" class="mt-2"></div>


                    <div class="col-md-4">
                        <label>Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}">
                        <span class="text-danger error-text apellido_paterno_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label>Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}">
                        <span class="text-danger error-text apellido_materno_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label>Nombre(s)</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                        <span class="text-danger error-text nombre_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>CURP</label>
                        <input type="text" name="curp" class="form-control" value="{{ old('curp') }}">
                        <span class="text-danger error-text curp_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label>Unidad Académica</label>
                        <select name="unidad_academica" id="unidad_academica" class="form-select">
                            <option value="">Selecciona una unidad</option>
                            @foreach($unidades as $abreviatura => $nombre)
                            <option value="{{ $abreviatura }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text unidad_academica_error"></span>
                    </div>
                    <div class="col-md-4">
                         <label>Programa Académico</label>
                         <select name="programa_academico" id="programa_academico" class="form-select">
                             <option value="">Selecciona primero una unidad</option>
                         </select>
                         <span class="text-danger error-text programa_academico_error"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Especialidad</label>
                        <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad') }}">
                        <span class="text-danger error-text especialidad_error"></span>
                    </div>
                    <div class="col-md-2">
                        <label>Situación académica</label>
                        <select name="situacion_academica" class="form-select">
                            <option value="" selected disabled>Selecciona...</option>
                            <option value="INSCRITO" {{ old('situacion_academica')=='INSCRITO'?'selected':'' }}>Inscrito</option>
                            <option value="PASANTE" {{ old('situacion_academica')=='PASANTE'?'selected':'' }}>Pasante</option>
                            <option value="TITULADO" {{ old('situacion_academica')=='TITULADO'?'selected':'' }}>Titulado</option>
                        </select>
                        <span class="text-danger error-text situacion_academica_error"></span>
                    </div>
                    <div class="col-md-2">
                        <label>Género</label>
                        <select name="genero" class="form-select">
                            <option value="">Selecciona</option>
                            <option value="MASCULINO" {{ old('genero')=='MASCULINO'?'selected':'' }}>Masculino</option>
                            <option value="FEMENINO" {{ old('genero')=='FEMENINO'?'selected':'' }}>Femenino</option>
                            <option value="OTRO" {{ old('genero')=='OTRO'?'selected':'' }}>Otro</option>
                        </select>
                        <span class="text-danger error-text genero_error"></span>
                    </div>
                    <div class="col-md-2">
                        <label>Edad</label>
                        <input type="number" name="edad" class="form-control" value="{{ old('edad') }}">
                        <span class="text-danger error-text edad_error"></span>
                    </div>
                    <div class="col-md-2">
                        <label>Teléfono Celular</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                        <span class="text-danger error-text telefono_error"></span>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Institución de Seguridad Social</label>
                        <select name="institucion_ss" class="form-select">
                            <option value="">Selecciona</option>
                            <option value="IMSS" {{ old('institucion_ss')=='IMSS'?'selected':'' }}>IMSS</option>
                            <option value="ISSSTE" {{ old('institucion_ss')=='ISSSTE'?'selected':'' }}>ISSSTE</option>
                            <option value="BIENESTAR" {{ old('institucion_ss')=='BIENESTAR'?'selected':'' }}>BIENESTAR</option>
                            <option value="PRIVADO" {{ old('institucion_ss')=='PRIVADO'?'selected':'' }}>PRIVADO</option>
                        </select>
                        <span class="text-danger error-text institucion_ss_error"></span>
                    </div>
                    <div class="col-md-3">
                        <label>NSS</label>
                        <input type="text" name="nss" class="form-control" value="{{ old('nss') }}">
                        <span class="text-danger error-text nss_error"></span>
                    </div>
                         <div class="col-md-4">
                        <label>CLABE Interbancaria</label>
                        <input type="text" name="clabe" class="form-control" value="{{ old('clabe') }}">
                        <span class="text-danger error-text clabe_error"></span>
                    </div>
                    <div class="col-md-2">
                        <label>Entidad Bancaria</label>
                        <select name="banco" class="form-select">
                            <option value="">Selecciona banco</option>
                            <option value="BBVA" {{ old('banco')=='BBVA'?'selected':'' }}>BBVA</option>
                            <option value="SANTANDER" {{ old('banco')=='SANTANDER'?'selected':'' }}>Santander</option>
                            <option value="BANORTE" {{ old('banco')=='BANORTE'?'selected':'' }}>Banorte</option>
                            <option value="HSBC" {{ old('banco')=='HSBC'?'selected':'' }}>HSBC</option>
                            <option value="BANCO AZTECA" {{ old('banco')=='BANCO AZTECA'?'selected':'' }}>Banco Azteca</option>
                            <option value="BANCOPPEL" {{ old('banco')=='BANCOPPEL'?'selected':'' }}>Bancoppel</option>
                        </select>
                        <span class="text-danger error-text banco_error"></span>
                    </div>
                </div>

               {{-- DOMICILIO BRIGADISTA --}}
            <h5 class="mb-3 mt-4">Domicilio</h5>
               <div class="domicilio-container">
                 <div class="row mb-3">
                 <div class="col-md-4">
                 <div class="input-group">
                     <input type="text" name="cp" class="form-control cp-input" placeholder="Ingresa tu C.P." value="{{ old('cp') }}">
                     <button type="button" class="btn btn-primary btn-buscar-cp">Buscar</button>
                     <button type="button" class="btn btn-secondary btn-limpiar-cp">Limpiar</button>
                 </div>
            </div>
                <div class="col-md-4">
                     <input type="text" name="estado" class="form-control estado-input" placeholder="Estado" value="{{ old('estado') }}">
                </div>
                <div class="col-md-4">
                     <input type="text" name="municipio" class="form-control municipio-input" placeholder="Municipio" value="{{ old('municipio') }}">
                </div>
            </div>

                <div class="row mb-2">
                <div class="col-md-4">
                     <input type="text" name="calle" class="form-control" placeholder="Calle" value="{{ old('calle') }}">
                </div>
                <div class="col-md-2">
                     <input type="text" name="no_ext" class="form-control" placeholder="No. Ext." value="{{ old('no_ext') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="no_int" class="form-control" placeholder="No. Int." value="{{ old('no_int') }}">
                </div>
                <div class="col-md-4">
                     <select name="colonia" class="form-select colonia-select" data-target="domicilio" data-old="{{ strtoupper(old('colonia')) }}">
                            <option value="">Selecciona una colonia</option>
                     </select>
                    </div>
               </div>
           </div>

                <div class="mb-3">
                    <label>Foto (JPG/PNG, máximo 1MB)</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                    <img id="fotoPreview" src="#" alt="Vista previa" style="max-width: 200px; margin-top: 10px; display:none;">
                    <span class="text-danger error-text foto_error"></span>
                </div>

                {{-- CONTACTO DE EMERGENCIA --}}
                <h5 class="mb-3 mt-4">Contacto de Emergencia</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Nombre completo</label>
                        <input type="text" name="contacto_nombre" class="form-control" value="{{ old('contacto_nombre') }}">
                        <span class="text-danger error-text contacto_nombre_error"></span>
                    </div>
                    <div class="col-md-4">
                        <label>Parentesco</label>
                        <select name="contacto_parentesco" class="form-select">
                            <option value="">Selecciona</option>
                            <option value="PADRE" {{ old('contacto_parentesco')=='PADRE'?'selected':'' }}>Padre</option>
                            <option value="MADRE" {{ old('contacto_parentesco')=='MADRE'?'selected':'' }}>Madre</option>
                            <option value="HERMANO" {{ old('contacto_parentesco')=='HERMANO'?'selected':'' }}>Hermano</option>
                            <option value="HERMANA" {{ old('contacto_parentesco')=='HERMANA'?'selected':'' }}>Hermana</option>
                            <option value="AMIGO" {{ old('contacto_parentesco')=='AMIGO'?'selected':'' }}>Amigo</option>
                            <option value="AMIGA" {{ old('contacto_parentesco')=='AMIGA'?'selected':'' }}>Amiga</option>
                            <option value="OTRO" {{ old('contacto_parentesco')=='OTRO'?'selected':'' }}>Otro</option>
                        </select>
                        <span class="text-danger error-text contacto_parentesco_error"></span>
                    </div>
                     <div class="col-md-4">
                           <label>Teléfono de contacto</label>
                           <input type="text" name="telefono_contacto" class="form-control" value="{{ old('telefono_contacto') }}">
                        <span class="text-danger error-text telefono_contacto_error"></span>
                    </div>
                </div>

               {{-- DOMICILIO CONTACTO --}}
              <h5 class="mb-3 mt-4">Domicilio Contacto</h5>
              <div class="domicilio-container">
              <div class="row mb-3">
                <div class="col-md-4">
              <div class="input-group">
                   <input type="text" name="cp_contacto" class="form-control cp-input" placeholder="Ingresa tu C.P." value="{{ old('cp_contacto') }}">
                   <button type="button" class="btn btn-primary btn-buscar-cp">Buscar</button>
                   <button type="button" class="btn btn-secondary btn-limpiar-cp">Limpiar</button>
              </div>
         </div>
                <div class="col-md-4">
                     <input type="text" name="estado_contacto" class="form-control estado-input" placeholder="Estado" value="{{ old('estado_contacto') }}">
                </div>
                <div class="col-md-4">
                     <input type="text" name="municipio_contacto" class="form-control municipio-input" placeholder="Municipio" value="{{ old('municipio_contacto') }}">
                </div>
            `</div>

                <div class="row mb-2">
                <div class="col-md-4">
                     <input type="text" name="calle_contacto" class="form-control" placeholder="Calle" value="{{ old('calle_contacto') }}">
                </div>
                <div class="col-md-2">
                     <input type="text" name="no_ext_contacto" class="form-control" placeholder="No. Ext." value="{{ old('no_ext_contacto') }}">
                </div>
                <div class="col-md-2">
                     <input type="text" name="no_int_contacto" class="form-control" placeholder="No. Int." value="{{ old('no_int_contacto') }}">
                </div>
                <div class="col-md-4">
                    <select name="colonia_contacto" class="form-select colonia-select" data-target="contacto" data-old="{{ strtoupper(old('colonia_contacto')) }}">
                          <option value="">Selecciona una colonia</option>
                    </select>
                </div>
             </div>
        </div>
        
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}">
                    <span class="text-danger error-text correo_error"></span>
                </div>
                <div class="col-md-4">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <span class="text-danger error-text password_error"></span>
                </div>
                <div class="col-md-4">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    <span class="text-danger error-text password_confirmation_error"></span>
                </div>
             </div>
              <div class="mt-4 text-end">
              <button type="submit" id="btn-guardar" class="btn text-white" style="background-color:#6A1B4D;">Guardar datos</button>
            </div>

            </form>
        </div>
    </div>
</div>
<div id="mensaje-exito" class="mt-3"></div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    // Vista previa de la foto
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('fotoPreview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Convertir a mayúsculas excepto campos específicos
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (!['correo', 'password', 'password_confirmation', 'captcha'].includes(input.name)) {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    });

    // Relación Unidad → Programa
    const programasPorUnidad = @json($programas);

    $('#unidad_academica').on('change', function() {
        let unidad = $(this).val();
        let programas = programasPorUnidad[unidad] || [];
        $('#programa_academico').empty().append('<option value="">Selecciona un programa</option>');
        programas.forEach(function(p) {
            $('#programa_academico').append('<option value="'+p+'">'+p+'</option>');
        });
    });

    // Envío del formulario por AJAX
    $(document).ready(function() {
        $('#form-brigadista').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            $('#mensaje-exito').html('');

            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    form.reset();
                    $('#mensaje-exito').html(`
                        <div class="alert alert-success" role="alert">
                            Brigadista creado correctamente. 
                        </div>
                    `);
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value){
                            $('.' + key + '_error').text(value[0]);
                        });
                    }
                }
            });
        });
    });
</script>
<script>
const escuelas = {
    "30": "ESIME_ZACATENCO",
    "31": "ESIA_ZACATENCO",
    "32": "ESIQIE",
    "33": "ESFM",
    "34": "ESIT",
    "35": "ESIME_CULHUACAN",
    "36": "ESIME_AZCAPOTZALCO",
    "37": "ESIME_TICOMAN",
    "38": "ESIA_TECAMACHALCO",
    "39": "ESIA_TICOMAN",
    "40": "ESCA_UST",
    "41": "ESE",
    "42": "EST",
    "43": "ESCA_UT",
    "44": "ENBA",
    "45": "UPIEM",
    "50": "ENCB",
    "51": "ESM",
    "52": "ENMH",
    "53": "ESEO",
    "60": "UPIICSA",
    "61": "CICS_UMA",
    "62": "UPIBI",
    "63": "ESCOM",
    "64": "UPIITA",
    "65": "CICS_UST",
    "66": "UPIIG",
    "67": "UPIIZ",
    "68": "UPIIH",
    "69": "UPIIC",
    "70": "UPIIP",
    "71": "UPIIT",
};

document.getElementById('btnBuscarAlumno').addEventListener('click', function() {
    const boleta = document.getElementById('numero_boleta').value.trim();

    if (!boleta) {
        alert('Por favor ingresa la boleta.');
        return;
    }

    document.getElementById('resultado-busqueda').innerHTML =
        '<div class="alert alert-secondary">Buscando alumno...</div>';

    fetch('{{ route('buscar.alumno') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ boleta })
    })
    .then(response => response.json())
    .then(data => {
        const form = document.getElementById('form-brigadista');
        const campos = form ? form.querySelectorAll('input, select, textarea, button') : [];

        // Función para bloquear campos excepto boleta y sus botones
        //function bloquearCampos(bloquear) {
           // campos.forEach(el => {
              //  if (el.id === 'numero_boleta' || el.id === 'btnBuscarAlumno' || el.id === 'btnLimpiarFormulario') {
             //       el.disabled = false;
              //      return;
             //   }
             //   el.disabled = bloquear;
            //    if (bloquear) el.classList.add('campo-bloqueado');
             //   else el.classList.remove('campo-bloqueado');
       //     });
     //   }

      //  if (data.error) {
      //      document.getElementById('resultado-busqueda').innerHTML =
      //          `<div class="alert alert-danger">${data.error}</div>`;
      //      bloquearCampos(true);
      //      return;
      //  }

      //  if (!data.alumno) {
      //      document.getElementById('resultado-busqueda').innerHTML =
      //          `<div class="alert alert-danger">No se encontraron registros.</div>`;
      //      bloquearCampos(true);
      //      return;
      //  }

        const alumno = data.alumno;

        // Validar estatus
        const estatus = alumno.Estatus_Alumno?.trim().toUpperCase() || '';
        if (estatus === 'A') {
            document.getElementById('resultado-busqueda').innerHTML = `
                <div class="alert alert-warning">
                    El alumno <strong>${alumno.Nombre} ${alumno.Apellido_pat} ${alumno.Apellido_mat}</strong> no puede realizar el registro.
                </div>`;
          //  bloquearCampos(true);
            return;
        }

        if (estatus !== 'I' && estatus !== 'E') {
            document.getElementById('resultado-busqueda').innerHTML = `
                <div class="alert alert-danger">
                    El estatus del alumno es desconocido (${estatus}). No se puede continuar con el registro.
                </div>`;
         //   bloquearCampos(true);
            return;
        }

        // Alumno válido: desbloquear campos
        //bloquearCampos(false);

        // Mostrar info del alumno
        document.getElementById('resultado-busqueda').innerHTML = `
            <div class="alert alert-success">
                <strong>Alumno encontrado:</strong> ${alumno.Nombre} ${alumno.Apellido_pat} ${alumno.Apellido_mat}
            </div>`;

        // Rellenar campos
        document.querySelector('input[name="apellido_paterno"]').value = alumno.Apellido_pat || '';
        document.querySelector('input[name="apellido_materno"]').value = alumno.Apellido_mat || '';
        document.querySelector('input[name="nombre"]').value = alumno.Nombre || '';
        document.querySelector('input[name="curp"]').value = alumno.CURP || '';
        document.querySelector('input[name="correo"]').value = alumno.E_Mail_Personal || '';
        document.querySelector('input[name="telefono"]').value = alumno.Tel_movil || alumno.Telefono || '';

        const progSelect = document.querySelector('select[name="programa_academico"]');
        if (progSelect && alumno.Programa_Academico) {
            let opt = [...progSelect.options].find(o => o.text.trim() === alumno.Programa_Academico.trim());
            if (opt) progSelect.value = opt.value;
            else {
                const nueva = document.createElement('option');
                nueva.value = alumno.Programa_Academico;
                nueva.text = alumno.Programa_Academico;
                progSelect.appendChild(nueva);
                progSelect.value = alumno.Programa_Academico;
            }
        }

        const uniSelect = document.querySelector('select[name="unidad_academica"]');
        if (uniSelect && alumno.Escuela) {
            uniSelect.value = escuelas[alumno.Escuela] || "";
        }

        const generoSelect = document.querySelector('select[name="genero"]');
        if (generoSelect && alumno.Sexo) {
            generoSelect.value = alumno.Sexo == '0' ? 'FEMENINO' : 'MASCULINO';
        }

        // Bloquear campos de nombre, apellidos y CURP
        ['nombre','apellido_paterno','apellido_materno','curp'].forEach(name => {
            const campo = document.querySelector(`input[name="${name}"]`);
            if (campo) campo.readOnly = true;
        });

        if (uniSelect) {
            uniSelect.style.pointerEvents = "none";
            uniSelect.style.backgroundColor = "#eee";
        }
        if (progSelect) {
            progSelect.style.pointerEvents = "none";
            progSelect.style.backgroundColor = "#eee";
        }
    })
    .catch(error => {
        console.error(error);
        document.getElementById('resultado-busqueda').innerHTML =
            `<div class="alert alert-danger">Error al conectar con el servicio.</div>`;
    });
});

// Limpiar formulario
document.getElementById('btnLimpiarFormulario').addEventListener('click', function() {
    document.getElementById('numero_boleta').value = '';

    document.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => {
        input.value = '';
        input.readOnly = false;
    });

    document.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
        select.disabled = false;
    });

    document.getElementById('resultado-busqueda').innerHTML = '';

    bloquearCampos(false);
});

// Función global para bloquear campos, usada en limpiar también
function bloquearCampos(bloquear) {
    const form = document.getElementById('form-brigadista');
    if (!form) return;
    const campos = form.querySelectorAll('input, select, textarea, button');
    campos.forEach(el => {
        if (el.id === 'numero_boleta' || el.id === 'btnBuscarAlumno' || el.id === 'btnLimpiarFormulario') {
            el.disabled = false;
            return;
        }
        el.disabled = bloquear;
        if (bloquear) el.classList.add('campo-bloqueado');
        else el.classList.remove('campo-bloqueado');
    });
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.domicilio-container').forEach(function(container) {
        const cpInput = container.querySelector('.cp-input');
        const estadoInput = container.querySelector('.estado-input');
        const municipioInput = container.querySelector('.municipio-input');
        const coloniaSelect = container.querySelector('.colonia-select');
        const btnBuscar = container.querySelector('.btn-buscar-cp');
        const btnLimpiar = container.querySelector('.btn-limpiar-cp');

        // Guardamos el old value en mayúsculas para comparar
        const oldValue = (coloniaSelect.dataset.old || '').toUpperCase();

        // Botón buscar CP
        btnBuscar.addEventListener('click', function() {
            const cp = cpInput.value.trim();
            if (!cp) return;

            estadoInput.value = '';
            municipioInput.value = '';
            coloniaSelect.innerHTML = '<option value="">Selecciona colonia</option>';

            fetch('{{ route("buscar.cp") }}?cp=' + cp)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert('Error al consultar el código postal: ' + data.error);
                        return;
                    }

                    // Forzar mayúsculas
                    estadoInput.value = (data.estado || '').toUpperCase();
                    municipioInput.value = (data.municipio || '').toUpperCase();

                     // Bloquear los campos
                    estadoInput.readOnly = true;
                    municipioInput.readOnly = true;

                     // Llenar colonias en mayúsculas (validando)
                    if (Array.isArray(data.colonias)) {
                        data.colonias.forEach(col => {
                            if (typeof col === 'string' && col.trim() !== '') {
                                const opt = document.createElement('option');
                                opt.value = col.toUpperCase();
                                opt.text = col.toUpperCase();
                                if (oldValue && col.toUpperCase() === oldValue) {
                                    opt.selected = true;
                                }
                                coloniaSelect.appendChild(opt);
                            }
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al consultar el código postal.');
                });
        });

        // Botón limpiar CP
        btnLimpiar.addEventListener('click', function() {
            cpInput.value = '';
            estadoInput.value = '';
            municipioInput.value = '';
            coloniaSelect.innerHTML = '<option value="">Selecciona colonia</option>';
            estadoInput.readOnly = false;
            municipioInput.readOnly = false;
        });

        // Limpiar automáticamente al borrar el CP
        cpInput.addEventListener('input', function() {
            if (!this.value.trim()) {
                estadoInput.value = '';
                municipioInput.value = '';
                coloniaSelect.innerHTML = '<option value="">Selecciona colonia</option>';
                estadoInput.readOnly = false;
                municipioInput.readOnly = false;
            }
        });
    });
});
</script>
@endsection