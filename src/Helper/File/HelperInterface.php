<?php

namespace Kematjaya\ReportBundle\Helper\File;

use Symfony\Component\HttpFoundation\File\File;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface HelperInterface 
{
    public function download(string $fileName);
    
    public function upload(File $file, string $path):string;
}
