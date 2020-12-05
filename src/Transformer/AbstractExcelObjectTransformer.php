<?php

namespace Kematjaya\ReportBundle\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
abstract class AbstractExcelObjectTransformer implements ExcelObjectTransformerInterface 
{
    protected $entityManager, $translator;
    
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator) 
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }
    
    abstract public function getColumns():array;
    
    protected function checkConstraints(array $data):array
    {
        $columns = $this->getColumns();
        foreach($columns as $k => $v)
        {
            if(!isset($v[self::KEY_FIELD]))
            {
                throw new \Exception(sprintf('required key : %s', self::KEY_FIELD));
            }
            if(!isset($v[self::KEY_INDEX]))
            {
                throw new \Exception(sprintf('required key : %s', self::KEY_INDEX));
            }
            
            $field  = $v[self::KEY_FIELD];
            $index  = $v[self::KEY_INDEX];
            $type   = (isset($v[self::KEY_TYPE])) ? $v[self::KEY_TYPE] : null;
            switch($type)
            {
                case self::CONSTRAINT_TYPE_NUMBER:
                    $data[$index] = (float) $data[$index];
                    break;
                default:
                    break;
            }
            
            $constraints = (isset($v[self::KEY_CONSTRAINT])) ? $v[self::KEY_CONSTRAINT] : [];
            
            if(isset($constraints[self::CONSTRAINT_REQUIRED]) and $constraints[self::CONSTRAINT_REQUIRED] and !$data[$index])
            {
                throw new \Exception(sprintf('%s %s %s', $this->translator->trans('column'), $this->translator->trans($field), $this->translator->trans(self::CONSTRAINT_REQUIRED)));
            }
            
            if(isset($constraints[self::CONSTRAINT_REFERENCE_CLASS]))
            {
                if(!isset($constraints[self::CONSTRAINT_REFERENCE_FIELD]))
                {
                    throw new \Exception(sprintf('required "%s" constraint key', self::CONSTRAINT_REFERENCE_FIELD));
                }
                
                $referenceClass = $constraints[self::CONSTRAINT_REFERENCE_CLASS];
                $referenceField = $constraints[self::CONSTRAINT_REFERENCE_FIELD];
                
                $class = $this->entityManager->getRepository($referenceClass)->findOneBy([$referenceField => $data[$index]]);
                if($class and isset($constraints[self::CONSTRAINT_UNIQUE]) and $constraints[self::CONSTRAINT_UNIQUE])
                {
                    throw new \Exception(sprintf('%s %s %s', $this->translator->trans($field), $data[$index], $this->translator->trans('already_exist')));
                }
                
                $data[$index] = ($class) ? $class : $data[$index];
            }
        }
        
        return $data;
    }
}
