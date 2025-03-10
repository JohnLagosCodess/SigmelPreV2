<?php

namespace App\Exports;

use App\Models\sigmel_probando;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
/* SE USA WithHeadings PARA CUANDO EL ARCHIVO A DESCARGAR TIENE NOMBRE EN SUS COLUMNAS */
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProbandoExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return sigmel_probando::all();

        return sigmel_probando::on('mysql2')->select('nombre', 'created_at', 'updated_at')->get();

    }

    public function headings(): array
    {
        return ["Nombre", "Fecha Creacion", "Fecha Actualizacion"];
    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'size' => 12
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FED600'],
                ],

                // 'top' => [
                //     'borderStyle' => Border::BORDER_THIN,
                // ],
                // 'bottom' => [
                //     'borderStyle' => Border::BORDER_THIN,
                // ],
                // 'left' => [
                //     'borderStyle' => Border::BORDER_THIN,
                // ],
                // 'right' => [
                //     'borderStyle' => Border::BORDER_THIN,
                // ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '3F4293',
                ]
            ]
        ];

        $styleArray1 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '1E1E1E'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '43B4FF',
                ]
            ]
        ];

        $sheet->getStyle('A1:C1')->applyFromArray($styleArray);
        $sheet->getStyle('A1:C1')->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));

        $sheet->getStyle('A2:C6')->applyFromArray($styleArray1);
    }
    
   
}
