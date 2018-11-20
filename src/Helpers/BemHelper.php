<?php

namespace KAGagnon\BemPhp\Helpers;


class BemHelper{

    /**
     * BEM configurations
     */

    /**
     * Separator between block and element
     */
    public static $element_separator = '__';

    /**
     * Separator between modifer and block/element
     */
    public static $modifier_separator = '--';

    /**
     * Should @bem create a tag element
     */
    public static $create_tag = false;

    /**
     * If create_tag is true, what's the default tag name
     */
    public static $default_tag = 'div';

    /**
     * Prefix of BEM classes.
     */
    public static $block_prefix = '';

    /**
     * END :: BEM configurations
     */

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

        $default_tag = self::$default_tag;

        if( empty( $tag_name ) ){
            if( self::$create_tag ){
                $tag_name = self::$default_tag;
            }
        }else if( is_array( $tag_name ) ){
            $attributes = $tag_name;
            $tag_name = $default_tag;
        }

        self::$block_tag[] = $tag_name;

        if( $tag_name ){
            $class_in_attributes = isset( $attributes[ 'class' ] ) ? $attributes[ 'class' ] :  '';
            $attributes[ 'class' ] = $class_in_attributes . " " . self::getBemClass( false, isset( $attributes[ '_modifiers' ] ) ? $attributes[ '_modifiers' ] : [] );
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
    public static function getBemClass( $element = '', $modifiers = [], $parent_level = 0 ){
        $block = self::$block_scope[ count( self::$block_scope ) - 1 - $parent_level ];
        if( is_array( $element ) ){
            $modifiers = $element;
            $element = '';
        }

        if( is_string( $modifiers )|| is_numeric( $modifiers ) && trim( $modifiers ) ){
            $modifiers = explode( ' ', (string) $modifiers );
        }

        $block_prefix = self::$block_prefix;
        $el_sep = self::$element_separator;
        $mod_sep = self::$modifier_separator;

        $full_class = $block_prefix.$block;

        if( $element ){
            $full_class .= $el_sep.$element;
        }

        $all_classes = [ $full_class ];
        if( !empty( $modifiers ) ){
            foreach( $modifiers as $key => $modifier ){
                if( is_string( $key ) ){
                    if( $modifier ){
                        $all_classes[] = $full_class . $mod_sep . $key;
                    }
                }elseif( $modifier ){
                    $all_classes[] = $full_class . $mod_sep . $modifier;
                }
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
