<?php

namespace KAGagnon\BEMBlade\Helpers;


class BEMHelper{

    /**
     * Each created scope
     *
     * @var array
     */
    protected static $block_scope = [ '' ];

    /**
     * Create a new block scope
     *
     * @param $block_name Name of the BEM block
     */
    public static function startBlock( $block_name ){
        self::$block_scope[] = $block_name;
    }

    /**
     * @param string|array $element   Name of element or array of modifier
     * @param array        $modifiers Array of modifier
     */
    public static function bemClass( $element = '', $modifiers = [] ){
        $block = array_last( self::$block_scope );
        if( is_array( $element ) ){
            $modifiers = $element;
            $element = '';
        }

        if( is_string( $modifiers ) ){
            $modifiers = explode( ' ', $modifiers );
        }

        $el_sep = config( 'kagagnon.bem-blade.element_separator', '__' );
        $mod_sep = config( 'kagagnon.bem-blade.modifier_separator', '--' );

        $full_class = $block;

        if( $element ){
            $full_class .= $el_sep.$element;
        }

        if( empty( $modifiers ) ){
            echo $full_class;
        }else{
            $all_classes = [];

            foreach( $modifiers as $modifier ){
                $all_classes[] = $full_class.$mod_sep.$modifier;
            }

            echo implode( ' ', $all_classes);
        }
    }

    /**
     * End BEM block scope
     */
    public static function endBlock(){
        if( count( self::$block_scope ) > 1 )
            array_pop( self::$block_scope );
    }
}