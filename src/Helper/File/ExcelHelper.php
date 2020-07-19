<?php

namespace Kematjaya\ReportBundle\Helper\File;

use Kematjaya\ReportBundle\Helper\File\HelperInterface;
use Symfony\Component\HttpFoundation\File\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ExcelHelper implements HelperInterface
{
    private $spreadsheet;
    
    public function __construct(Spreadsheet $spreadsheet = null) 
    {
        $this->spreadsheet = $spreadsheet;
    }
    
    public function download(string $fileName) 
    {
        if(!$this->spreadsheet instanceof Spreadsheet)
        {
            throw new \Exception('undefined %s object', Spreadsheet::class);
        }
        
        $writer = new Xlsx($this->spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return $tempFile;
    }

    public function upload(File $file, string $path):string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName    = md5($originalFilename).uniqid().'.'.$file->guessExtension();
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        
        $uploaded = $file->move($path, $newFileName);
        if(!$uploaded)
        {
            throw new \Exception('failed to upload file');
        }

        return $path . DIRECTORY_SEPARATOR . $newFileName;
    }

}
