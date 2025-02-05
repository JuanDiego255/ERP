<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ContactsReportExport implements FromView, WithEvents, WithColumnWidths
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        $this->report->transform(function ($row) {
            $row->name = Str::ascii($row->name);
            $row->email = Str::ascii($row->email);
            return $row;
        });

        return view('exports.contacts_report', [
            'report' => $this->report
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 30,  // Nombre (más ancho)
            'C' => 40,  // Email (más ancho)
            'E' => 15,  // Deuda a pagar
            'F' => 20,  // Última fecha de pago
            'G' => 15,  // Sumatoria
            'H' => 15,  // Sumatoria
            
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 🔹 1. Agregar logo en la esquina izquierda (A1)
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo de Autos Grecia S.R.L.');
                $drawing->setPath(public_path('images/logo_ag.png')); // Ruta del logo en `public/images`
                $drawing->setHeight(100); // Ajusta la altura del logo
                $drawing->setCoordinates('A1'); // Posición en la celda A1
                $drawing->setWorksheet($sheet);

                // 🔹 2. Agregar el título "Reporte de Clientes - Autos Grecia S.R.L." centrado en A3:G3
                $sheet->mergeCells('A3:G3'); // Unir celdas para el título
                $sheet->setCellValue('A3', 'Reporte de Clientes - Autos Grecia S.R.L.'); // Texto del título
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Centrar texto
                $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(14); // Fuente más grande y negrita

                // 🔹 3. Ajustar altura de filas
                $sheet->getRowDimension(1)->setRowHeight(50); // Altura del logo
                $sheet->getRowDimension(3)->setRowHeight(30); // Espacio para el título

                // 🔹 4. Alinear columnas específicas
                $sheet->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Teléfono a la izquierda
                $sheet->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Deuda a la derecha
                $sheet->getStyle('F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}
