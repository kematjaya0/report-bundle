<?php

namespace Kematjaya\ReportBundle\Entity;

use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ExcelInterface 
{
    public function getHeader():?array;
    
    public function getArrayValue() :?array;
    
    public function setTranslator(TranslatorInterface $translator);
}
