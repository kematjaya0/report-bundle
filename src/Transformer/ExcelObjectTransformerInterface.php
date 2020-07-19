<?php

namespace Kematjaya\ReportBundle\Transformer;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ExcelObjectTransformerInterface extends ObjectTransformerInterface
{
    const CONSTRAINT_REQUIRED = 'required';
    const CONSTRAINT_UNIQUE = 'unique';
    const CONSTRAINT_REFERENCE_CLASS = 'reference_class';
    
    public function startReadedRow():int;
    
    public function fromArray(array $data);
    
    public function toArray($object):array;
}
