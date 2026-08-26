<?php

namespace App\Exports;

use App\Models\Brigadista;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BrigadistasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Brigadista::select(
            'brigadistas.numero_boleta',
            'brigadistas.apellido_paterno',
            'brigadistas.apellido_materno',
            'brigadistas.nombre',
            'brigadistas.curp',
            'brigadistas.unidad_academica',
            'brigadistas.programa_academico',
            'brigadistas.especialidad',
            'brigadistas.situacion_academica',
            'brigadistas.genero',
            'brigadistas.edad',
            'brigadistas.telefono',
            'brigadistas.institucion_ss',
            'brigadistas.nss',
            'brigadistas.clabe',
            'brigadistas.banco',
            'brigadistas.correo',
            'brigadistas.calle',
            'brigadistas.no_int',
            'brigadistas.no_ext',
            'brigadistas.colonia',
            'brigadistas.municipio',
            'brigadistas.estado',
            'brigadistas.cp',
            'brigadistas.contacto_nombre',
            'brigadistas.contacto_parentesco',
            'brigadistas.calle_contacto',
            'brigadistas.no_int_contacto',
            'brigadistas.no_ext_contacto',
            'brigadistas.colonia_contacto',
            'brigadistas.municipio_contacto',
            'brigadistas.estado_contacto',
            'brigadistas.cp_contacto',
            'brigadistas.telefono_contacto',
            'registros.rol_deseado',
            'registros.modalidad',
            'registros.talla',
            'registros.siss',
            'registros.participacion_anterior',
            'registros.municipio_anterior',
            'registros.status as estatus',
            'asignaciones.estado_asignado',
            'asignaciones.municipio_asignado',
            'asignaciones.proyecto_asignado'
        );

        $query->leftJoin('registros', function ($join) {
            $join->on(
                'brigadistas.id',
                '=',
                'registros.brigadista_id'
            )->whereRaw(
                'registros.id = (
                    SELECT MAX(r2.id)
                    FROM registros r2
                    WHERE r2.brigadista_id = brigadistas.id
                )'
            );
        });

        $query->leftJoin(
            'asignaciones',
            'asignaciones.brigadista_id',
            '=',
            'brigadistas.id'
        );

        if ($this->request && $this->request->filled('numero_boleta')) {
            $query->where(
                'brigadistas.numero_boleta',
                'like',
                '%' . $this->request->numero_boleta . '%'
            );
        }

        if ($this->request && $this->request->filled('programa_academico')) {
            $query->where(
                'brigadistas.programa_academico',
                $this->request->programa_academico
            );
        }

        if ($this->request && $this->request->filled('unidad_academica')) {
            $query->where(
                'brigadistas.unidad_academica',
                $this->request->unidad_academica
            );
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Número Boleta/Empleado',
            'Apellido Paterno',
            'Apellido Materno',
            'Nombre',
            'CURP',
            'Unidad Académica',
            'Programa Académico',
            'Especialidad',
            'Situación Académica',
            'Género',
            'Edad',
            'Teléfono',
            'Institución SS',
            'NSS',
            'CLABE',
            'Banco',
            'Correo',
            'Calle',
            'No. Int',
            'No. Ext',
            'Colonia',
            'Municipio',
            'Estado',
            'CP',
            'Contacto Nombre',
            'Contacto Parentesco',
            'Calle Contacto',
            'No. Int Contacto',
            'No. Ext Contacto',
            'Colonia Contacto',
            'Municipio Contacto',
            'Estado Contacto',
            'CP Contacto',
            'Teléfono Contacto',
            'Rol Deseado',
            'Modalidad',
            'Talla',
            'Registro en el SISS',
            'Participación Anterior',
            'Municipio Anterior',
            'Estatus',
            'Estado asignado',
            'Municipio asignado',
            'Proyecto asignado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AN1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1:AN1')
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle('A1:AN1')
            ->getAlignment()
            ->setVertical('center');

        $sheet->getStyle('A1:AR' . $sheet->getHighestRow())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestColumn = $sheet->getHighestColumn();

                $sheet->setAutoFilter(
                    "A1:{$highestColumn}1"
                );

                $sheet->getRowDimension(1)
                    ->setRowHeight(25);

                $sheet->getStyle(
                    'A1:' . $highestColumn . '1'
                )
                ->getFill()
                ->setFillType(
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                )
                ->getStartColor()
                ->setARGB('D9D9D9');
            },
        ];
    }
}