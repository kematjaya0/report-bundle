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
        $i = 0;
        foreach($columns as $k => $v)
        {
            if(isset($v[self::CONSTRAINT_REQUIRED]) and $v[self::CONSTRAINT_REQUIRED] and !$data[$i])
            {
                throw new \Exception(sprintf('%s %s %s', $this->translator->trans('column'), $this->translator->trans($k), $this->translator->trans(self::CONSTRAINT_REQUIRED)));
            }
            
            if(isset($v[self::CONSTRAINT_REFERENCE_CLASS]))
            {
                $referenceClass = $v[self::CONSTRAINT_REFERENCE_CLASS];
                $class = $this->entityManager->getRepository($referenceClass)->findOneBy(['code' => $data[$i]]);
                if($class and isset($v[self::CONSTRAINT_UNIQUE]) and $v[self::CONSTRAINT_UNIQUE])
                {
                    throw new \Exception(sprintf('%s %s %s', $this->translator->trans($k), $data[$i], $this->translator->trans('already_exist')));
                }
                
                $data[$i] = ($class) ? $class : $data[$i];
            }
            $i++;
        }
        
        return $data;
    }
}
