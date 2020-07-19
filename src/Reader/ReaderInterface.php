<?php

namespace Kematjaya\ReportBundle\Reader;

use Kematjaya\ReportBundle\Transformer\ObjectTransformerInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ReaderInterface 
{
    public function load(string $fileName, ObjectTransformerInterface $objectTransformer);
}
