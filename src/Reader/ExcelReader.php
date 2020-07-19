<?php

namespace Kematjaya\ReportBundle\Reader;

use Kematjaya\ReportBundle\Reader\ReaderInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ExcelReader implements ReaderInterface
{
    private $documentPath;
    
    public function __construct(ContainerBagInterface $containerBag) 
    {
        dump($containerBag->get('report'));exit;
    }
    
    public function load(string $fileName) 
    {
        dump($fileName);exit;
    }

    public function loadTemplates(string $fileName):Spreadsheet
    {
        if(!file_exists($fileName)) 
        {
            throw new \Exception('file ' . $fileName .' not exist');
        }
        
        $reader = IOFactory::createReader('Xlsx');
        return $reader->load($fileName);
    }

}
