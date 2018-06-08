<?php
namespace KAGagnon\BemTwig;

// use KAGagnon\BemTwig\BemTokenParser;
use KAGagnon\BemPhp\Helpers\BemHelper;
use Twig_Extension;
use Twig_Function;

class BemTwigExtension extends Twig_Extension{
    public function getTokenParsers(){
        return array(
            new BemTokenParser(),
        );
    }

    public function getFunctions(){
        return [
            new Twig_Function( 'bemclass', [ BemHelper::class, 'getBemClass' ] ),
        ];
    }
}
