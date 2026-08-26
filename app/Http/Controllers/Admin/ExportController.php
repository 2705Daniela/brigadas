<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrigadistasExport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $filename = $request->input(
            'filename',
            'brigadistas_' . date('Ymd_His') . '.xlsx'
        );

        if (!str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        return Excel::download(
            new BrigadistasExport($request),
            $filename
        );
    }
}