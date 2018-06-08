<?php
namespace KAGagnon\BemTwig;

use KAGagnon\BemPhp\Helpers\BemHelper;

use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

class BemNode extends Twig_Node{
    public function __construct( $nodes, $content, $line, $tag = null ){
        // var_dump($nodes);die;
        parent::__construct( $nodes, [], $line, $tag );
    }

    public function compile( Twig_Compiler $compiler ){
        $compiler
            ->addDebugInfo( $this )
            ->write( BemHelper::class . "::startBlock(" )
        ;

        $i = 1;
        while( true ){
            if( $this->hasNode( "arg$i" ) ){
                if( $i > 1 ) $compiler->write( ',' );
                $compiler->subcompile( $this->getNode( "arg$i" ) );
            }else{
                break;
            }
            $i++;
        }

        $compiler
            ->write( ")" )
            ->raw( ";\n" )
            ->subcompile( $this->getNode( 'content' ) )
            ->write( BemHelper::class . "::endBlock();" )
            ->raw( "\n" )
        ;
    }
}
