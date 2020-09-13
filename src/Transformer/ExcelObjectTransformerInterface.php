<?php

namespace Kematjaya\ReportBundle\Transformer;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
interface ExcelObjectTransformerInterface extends ObjectTransformerInterface
{
    const CONSTRAINT_REQUIRED           = 'required';
    const CONSTRAINT_UNIQUE             = 'unique';
    const CONSTRAINT_REFERENCE_CLASS    = 'reference_class';
    const CONSTRAINT_REFERENCE_FIELD    = 'reference_field';
    
    const CONSTRAINT_TYPE_NUMBER        = 'number';
    
    const KEY_INDEX           = 'index';
    const KEY_CONSTRAINT      = 'constraint';
    const KEY_FIELD           = 'field';
    const KEY_TYPE            = 'type';
    
    public function startReadedRow():int;
    
    public function fromArray(array $data);
    
    public function toArray($object):array;
}
