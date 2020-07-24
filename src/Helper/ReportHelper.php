<?php

namespace Kematjaya\ReportBundle\Helper;

use Kematjaya\ReportBundle\Builder\ExcelBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DomCrawler\Crawler;
use koolreport\KoolReport;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ReportHelper 
{
    private $translator, $excelBuilder;
    
    public function __construct(TranslatorInterface $translator, ExcelBuilder $excelBuilder) 
    {
        $this->translator = $translator;
        $this->excelBuilder = $excelBuilder;
    }
    
    public function buildReport(string $className, $filters = []): KoolReport
    {
        return new $className(['translator' => $this->translator, 'filter' => $filters]);
    }
    
    public function renderHTML(KoolReport $report, string $view = null, bool $return = true):string
    {
        return $report->run()->render($view, $return);
    }
    
    public function getTableContent(KoolReport $report, string $view = null):string
    {
        $html = $this->renderHTML($report, $view);
        $crawler = new Crawler($html);
        return sprintf('<table>%s</table>', $crawler->filter('table')->html());
    }
    
    public function toExcel(KoolReport $report, string $fileName = 'report.xls', string $view = null)
    {
        $table = $this->getTableContent($report, $view);
        return $this->excelBuilder->fromHtml($table, $fileName);
    }
}
