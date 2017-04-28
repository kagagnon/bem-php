<?php

namespace KAGagnon\BEMBlade\Blade;

use Illuminate\Support\Facades\Blade;

class BEM{
    /**
     * BEM helper class
     *
     * @var string
     */
    static private $helper_class = "\\KAGagnon\\BEMBlade\\Helpers\\BEMHelper";

    /**
     * Create blade directives
     *
     * @return void
     */
    public static function boot(){
        Blade::directive( 'bem', [ self::class, 'bem' ] );
        Blade::directive( 'endbem', [ self::class, 'endBem' ] );
        Blade::directive( 'bemclass', [ self::class, 'bemClass' ] );
    }

    public static function bem( $name ){
        return "<?php ".self::$helper_class."::startBlock( $name ) ?>";
    }

    public static function bemClass( $arguments ){
        return "<?php ".self::$helper_class."::bemClass( $arguments ) ?>";
    }

    public static function endBem(  ){
        return "<?php ".self::$helper_class."::endBlock() ?>";
    }
}