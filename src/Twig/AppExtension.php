<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends AbstractExtension {
    private $templating;

    public function getFilters() {
        return( array(
            new TwigFilter("showUitgenodigd", array($this, 'showUitgenodigd'))
        ));
    }

    public function showUitgenodigd($bool) {
        if(!$bool) {
            return('❌');
        }
        return('✅');
    }
}