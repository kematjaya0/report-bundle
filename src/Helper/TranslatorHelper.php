<?php

namespace Kematjaya\ReportBundle\Helper;

use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class TranslatorHelper 
{
    
    public static function trans(string $label, TranslatorInterface $translator = null)
    {
        if ($translator)
        {
            return $translator->trans($label);
        }

        return $label;
    }
    
}
