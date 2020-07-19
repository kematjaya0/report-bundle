<?php

namespace Kematjaya\ReportBundle\Reader;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ReaderInterface 
{
    public function load(string $fileName);
}
