<?php

namespace Kematjaya\ReportBundle\Style;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ExcelStyle 
{
    public static function getStyleSetting()
    {
        return array(
            'header' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
                    ]
                ]
            ],
            'row'   => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]   
        );
    }
}
