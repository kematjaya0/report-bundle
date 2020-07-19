<?php

namespace Kematjaya\ReportBundle\Reader;

use Kematjaya\ReportBundle\Reader\ReaderInterface;
use Kematjaya\ReportBundle\Transformer\ExcelObjectTransformerInterface;
use Kematjaya\ReportBundle\Transformer\ObjectTransformerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ExcelReader implements ReaderInterface
{
    private $documentPath, $fileSystem, $container;
    
    public function __construct(ContainerBagInterface $containerBag, ContainerInterface $container) 
    {
        $configs = $containerBag->get('kmj_report');
        $this->fileSystem = new Filesystem();
        if(!$this->fileSystem->exists($configs['import']['document_path']))
        {
            $this->fileSystem->mkdir($configs['import']['document_path'], 0777);
        }
        
        $this->documentPath = $configs['import']['document_path'];
        $this->container = $container;
    }
    
    public function getFileSystem():Filesystem
    {
        return $this->fileSystem;
    }
    
    public function getManager(): EntityManagerInterface
    {
        $manager = $this->container->get('doctrine')->getManager();
        if(!$manager instanceof EntityManagerInterface)
        {
            throw new \Exception('invalid doctrine manager');
        }
        
        return $manager;
    }
    
    public function load(string $fileName, ObjectTransformerInterface $objectTransformer) 
    {
        if(!$this->getFileSystem()->exists($fileName)) 
        {
            throw new \Exception('file ' . $fileName .' not exist');
        }
        
        if(!$objectTransformer instanceof ExcelObjectTransformerInterface)
        {
            throw new \Exception(sprintf('invalid format %s, required: %s instance', get_class($objectTransformer), ExcelObjectTransformerInterface::class));
        }
        
        try 
        {
            $reader         = (IOFactory::createReader('Xlsx'))->setReadDataOnly(true);
            $spreadsheet    = $reader->load($fileName);
            $manager        = $this->getManager();

            $data = ($spreadsheet->getActiveSheet()) ? $spreadsheet->getActiveSheet()->toArray():[];

            $results = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($data as $k => $v)
            {
                if($k < $objectTransformer->startReadedRow())
                {
                    continue;
                }

                $results->add($objectTransformer->fromArray($v));
            }

            $manager->flush();

            return $results;
        } catch (\Exception $ex) 
        {
            throw $ex;
        }
            
    }

    public function loadTemplates(string $fileName):Spreadsheet
    {
        $fileName = $this->documentPath . DIRECTORY_SEPARATOR . $fileName;
        if(!$this->getFileSystem()->exists($fileName)) 
        {
            throw new \Exception('file ' . $fileName .' not exist');
        }
        
        $reader = IOFactory::createReader('Xlsx');
        return $reader->load($fileName);
    }

}
