<?php
namespace KAGagnon\BemPhp\Twig;

// use KAGagnon\BemTwig\BemTokenParser;
use KAGagnon\BemPhp\Helpers\BemHelper;
use Twig_Extension;
use Twig_SimpleFunction;

class BemTwigExtension extends Twig_Extension{
    public function getTokenParsers(){
        return array(
            new BemTokenParser(),
        );
    }

    public function getFunctions(){
        return [
            new Twig_SimpleFunction( 'bemclass', [ BemHelper::class, 'getBemClass' ] ),
        ];
    }
}
