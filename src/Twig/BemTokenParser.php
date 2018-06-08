<?php
namespace KAGagnon\BemPhp\Twig;

use Twig_TokenParser;
use Twig_Token;

class BemTokenParser extends Twig_TokenParser{
    public function parse( Twig_Token $token ){
        $parser = $this->parser;
        $stream = $parser->getStream();
        $nodes = [];

        $stream->expect( Twig_Token::PUNCTUATION_TYPE, '(' );
        $nodes[ 'arg1' ] = $parser->getExpressionParser()->parseExpression();

        if( $stream->nextIf( Twig_Token::PUNCTUATION_TYPE, ',' ) ){
            $nodes[ 'arg2' ] = $parser->getExpressionParser()->parseExpression();
        }

        if( $stream->nextIf( Twig_Token::PUNCTUATION_TYPE, ',' ) ){
            $nodes[ 'arg3' ] = $parser->getExpressionParser()->parseExpression();
        }

        $stream->expect( Twig_Token::PUNCTUATION_TYPE, ')' );
        $stream->expect( Twig_Token::BLOCK_END_TYPE );

        $nodes[ 'content' ] = $parser->subparse( [ $this, 'decideIfEnd' ] );
        $stream->next();
        $stream->expect( Twig_Token::BLOCK_END_TYPE );

        return new BemNode( $nodes, $token->getLine(), $this->getTag() );
    }

    public function getTag(){
        return 'bem';
    }

    public function decideIfEnd( Twig_Token $token ){
        return $token->test( [ 'endbem' ] );
    }
}
