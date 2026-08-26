<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Brigadista;

class PDFController extends Controller
{
    public function generarPDF($id)
    {
        $brigadista = Brigadista::findOrFail($id);
        $pdf = Pdf::loadView('pdf.ficha', compact('brigadista'));
        return $pdf->download('Ficha_'.$brigadista->nombre.'.pdf');
    }
}


