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
     * Array of created tag
     *
     * @var array
     */
    protected static $block_tag = [];

    /**
     * Create a new block scope
     *
     * @param $block_name Name of the BEM block
     */
    public static function startBlock( $block_name, $tag_name = '', $attributes = [] ){
        self::$block_scope[] = $block_name;

        $default_tag = config( 'bem-blade.default_tag', 'div' );

        if( empty( $tag_name ) ){
            if( config( 'bem-blade.create_tag', false ) ){
                $tag_name = config( 'bem-blade.default_tag', 'div' );
            }
        }else if( is_array( $tag_name ) ){
            $attributes = $tag_name;
            $tag_name = $default_tag;
        }

        self::$block_tag[] = $tag_name;

        if( $tag_name ){
            $class_in_attributes = array_get( $attributes, 'class', '' );
            $attributes[ 'class' ] = $class_in_attributes . " " . self::getBemClass( false, array_get( $attributes, '_modifiers', [] ) );
            unset( $attributes[ '_modifiers' ] );

            $all_attributes = [];
            if( !empty( $attributes ) ){
                foreach( $attributes as $name => $value ){
                    if( is_string( $name ) ){
                        $all_attributes[] = $name . '="' . htmlentities( $value ) . '"';
                    }else{
                        $all_attributes[] = $value;
                    }
                }
            }

            echo "<$tag_name ".implode( ' ', $all_attributes )." >";
        }
    }

    public static function bemClass(){
        echo call_user_func_array( [ self::class, 'getBemClass' ], func_get_args() );
    }

    /**
     * @param string|array $element   Name of element or array of modifier
     * @param array        $modifiers Array of modifier
     */
    protected static function getBemClass( $element = '', $modifiers = [] ){
        $block = array_last( self::$block_scope );
        if( is_array( $element ) ){
            $modifiers = $element;
            $element = '';
        }

        if( is_string( $modifiers ) ){
            $modifiers = explode( ' ', $modifiers );
        }

        $block_prefix = config( 'bem-blade.block_prefix', '' );
        $el_sep = config( 'bem-blade.element_separator', '__' );
        $mod_sep = config( 'bem-blade.modifier_separator', '--' );

        $full_class = $block_prefix.$block;

        if( $element ){
            $full_class .= $el_sep.$element;
        }

        $all_classes = [ $full_class ];
        if( !empty( $modifiers ) ){
            foreach( $modifiers as $modifier ){
                $all_classes[] = $full_class.$mod_sep.$modifier;
            }
        }

        return implode( ' ', $all_classes);
    }

    /**
     * End BEM block scope
     */
    public static function endBlock(){
        if( count( self::$block_scope ) > 1 )
            array_pop( self::$block_scope );

        if( !empty( self::$block_tag ) ){
            $last_tag = array_pop( self::$block_tag );

            if( $last_tag ){
                echo "</$last_tag>";
            }
        }
    }
}
