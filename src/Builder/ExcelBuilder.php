<?php

namespace Kematjaya\ReportBundle\Builder;

use Kematjaya\ReportBundle\Entity\ExcelInterface;
use Kematjaya\ReportBundle\Style\ExcelStyle;
use Symfony\Contracts\Translation\TranslatorInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ExcelBuilder 
{
    private $translator;
    
    private $spreadsheet;
    
    private $sheet;
    
    private $startRow = 1;
    
    private $startColumn = 'A';
    
    function __construct(TranslatorInterface $translator) 
    {
        $this->translator   = $translator;
        $this->spreadsheet  = new Spreadsheet();
        $this->sheet        = $this->spreadsheet->getActiveSheet();
    }
    
    function setStartRow($start)
    {
        $this->startRow = $start;
    }
    
    function setStartColumn($start)
    {
        $this->startColumn = $start;
    }
    
    function setHeader($header) 
    {
        $row  = $this->startRow + 1;
        $column = $this->startColumn;
        foreach($header as $k => $v) 
        {
            
            $this->sheet->setCellValue($column . $row, strtoupper($v));
            $this->sheet->getColumnDimension($column)->setAutoSize(true);
            if($k < (count($header)-1)) 
            {
                $column++;
            }
            
        }
        
        $style = ExcelStyle::getStyleSetting();
        $this->sheet->getStyle($this->startColumn . $row. ':' . $column . $row)->applyFromArray($style['header']);
        
        $this->startRow = $row + 1;
    }
    
    function setRowValue($value, $row, $column) 
    {
        $style = ExcelStyle::getStyleSetting();
        $this->sheet->setCellValue($column . $row, $value);
        $this->sheet->getStyle($column . $row. ':' . $column . $row)->applyFromArray($style['row']);
    }
    
    function buildFromArray($data = [])
    {
        foreach($data as $k => $rowExcel) {
            $this->buildRow($rowExcel, $k);
        }
        return $this;
    }
    
    function buildRow(ExcelInterface $rowExcel, $key)
    {
        $rowExcel->setTranslator($this->translator);
        if($key == 0) {
            $this->setHeader($rowExcel->getHeader());
        }
        
        $row = $this->startRow + $key;
        $column = $this->startColumn;
        foreach($rowExcel->getArrayValue() as $k => $value) {
            
            $this->setRowValue($value, $row, $column);
            $column++;
        }
    }
    
    public function getExcel($fileName)
    {
        $typeFile = '.xlsx';
        if(!strpos($fileName, $typeFile)){
            $fileName = str_replace('.', '_', $fileName);
            $fileName .= $typeFile;
        }
        
        $this->spreadsheet->getActiveSheet()->setShowGridlines(false);
        
        $writer = new Xlsx($this->spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return $tempFile;
    }
    
    public function fromHtml(string $html, $fileName = 'export.xls')
    {
        $reader = new Html();
        $spreadsheet = $reader->loadFromString($html);
        
        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return $tempFile;
    }
}
