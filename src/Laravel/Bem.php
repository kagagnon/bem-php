<?php

namespace KAGagnon\BemPhp\Laravel;

use Illuminate\Support\Facades\Blade;
use KAGagnon\BemPhp\Helpers\BemHelper;

class Bem{
    /**
     * BEM helper class
     *
     * @var string
     */

    /**
     * Create blade directives
     *
     * @return void
     */
    public static function boot(){
        // Set configurations
        BemHelper::$element_separator = config( 'element_separator', '__' );
        BemHelper::$modifier_separator = config( 'modifier_separator', '--' );
        BemHelper::$create_tag = config( 'create_tag', false );
        BemHelper::$default_tag = config( 'default_tag', 'div' );
        BemHelper::$block_prefix = config( 'block_prefix', '' );

        // Create blade directive
        Blade::directive( 'bem', [ self::class, 'bem' ] );
        Blade::directive( 'endbem', [ self::class, 'endBem' ] );
        Blade::directive( 'bemclass', [ self::class, 'bemClass' ] );
    }

    public static function bem( $name ){
        return "<?php ".BemHelper::class."::startBlock( $name ) ?>";
    }

    public static function bemClass( $arguments ){
        return "<?php ".BemHelper::class."::bemClass( $arguments ) ?>";
    }

    public static function endBem(  ){
        return "<?php ".BemHelper::class."::endBlock() ?>";
    }
}
