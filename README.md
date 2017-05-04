# Installation

Install via composer with

```shell
$ composer install kagagnon/bem-blade
```
    
After successfully installing BEM Blade, add the service provider in your app configs.

```php
KAGagnon\BEMBlade\BEMServiceProvider::class,
```
    
The service provider will boot and register new directives in the Blade engine.

## Optional configurations

You can publish the config file with the following command:

```shell
$ php artisan vendor:publish --provider="KAGagnon\BEMBlade\BEMServiceProvider" --tag="config"
``` 
    
You can then change your element and modifier separators to your liking.

```php
<?php
return [

    /**
     * Separator between block and element
     *
     * Default: '__'
     */
    'element_separator' => '__',

    /**
     * Separator between modifer and block/element
     *
     * Default: '--'
     */
    'modifier_separator' => '--',

    /**
     * Should @bem create a tag element
     *
     * Default: false
     */
    'create_tag' => false,

    /**
     * If create_tag is true, what's the default tag name
     *
     * Default: 'div'
     */
    'default_tag' => 'div',

    /**
     * Prefix of BEM classes.
     *
     * Default: ''
     */
    'block_prefix' => '',
];
```

# How to use

## Blocks

You can create a new block with the directive `@bem( $block_name )`. Once the block if finished,
you can use `@endbem` to close the block. BEM block can be nest for sub-module. So this:

```blade
@bem( 'block' )
    @bem( 'other-block )
    @endbem
@endbem
```
    
is a valid syntax.

## Classes

To generate a class, you can use `@bemclass( [ string|array $element, [ string|array $modifiers ] ] )`.

- Passing no arguments generate the block name.
- Passing a string as first argument generate the block name with an element.
- Passing an array as first argument generate the block name with the modifiers.
- Passing a string and an array generate a block name with an element and its modifiers.
- Passing 2 strings generate a block name with an element and explode the string on spaces to generate the modifiers.

 Check the examples below: 
 
```blade
@bem( 'cup' ) // Init Block "cup"
    @bemclass() // Generate : cup
    @bemclass( [ 'glass' ] ) // Generate : cup--glass

    @bem( 'spoon' ) // Init Block "spoon"
        @bemclass // Generate : spoon
        @bemclass( [ 'metallic', 'cold' ] ) // Generate : spoon--metallic spoon--cold
        @bemclass( 'sugar' ) // Generate : spoon__sugar
        @bemclass( 'sugar', 'half-tea-spoon' ) // Generate : spoon__sugar--half-tea-spoon
    @endbem

    @bemclass( 'tea' ) // Generate : cup__tea
    @bemclass( 'coffee' ) // Generate : cup_coffee
    @bemclass( 'coffee' , 'with-sugar' ) // Generate : cup__coffe--with-sugar
    @bemclass( 'coffee' , [ 'with-sugar', 'with-milk'] ) // Generate : cup__coffee--with-sugar cup__coffee--with-milk
    @bemclass( 'coffee' , 'with-sugar with-milk no-foam' ) // Generate : cup__coffee--with-sugar cup__coffee--with-milk cup__coffee--no-foam
@endbem
```

## HTML example

```blade
@bem( 'article' )
   <div class="@bemclass">
       <h1 class="@bemclass( 'title' )">Article Name</h1>
       
       <p class="@bemclass( 'content' )">Article text...</p>
       
       @bem( 'meta' )
           <div class="@bemclass">
               <a href="..." class="@bemclass( 'link', 'inactive' )">0 comments</a>
               <a href="..." class="@bemclass( 'link', 'clear danger' )">Delete</a>
               <a href="..." class="@bemclass( 'link' )">Edit</a>
           </div>
       @endbem
   </div>
@endbem
```

Result to :

```html
<div class="article">
   <h1 class="article__title">Article Name</h1>
   
   <p class="article__content">Article text...</p>
   
   <div class="meta">
       <a href="..." class="meta__link--inactive">0 comments</a>
       <a href="..." class="meta__link--clear meta__link--danger">Delete</a>
       <a href="..." class="meta__link">Edit</a>
   </div>
</div>
```

# Create node with @bem

You can pass argument to `@bem` to automatically generate an HTML tag. 
To do so, you can pass the tag name as second argument and, optionally, an array of attributes.

You can also skip the tag name and pass an array as second argument. That will create an HTML element base on the `default_tag` configuration.

Additionally, if you set `create_tag` to true, `@bem()` will always create a tag base on
the `default_tag` configuration if only 1 argument is passed.

## Example

```blade
{{-- We assume `create_tag` is set to true --}}
@bem( 'block' ) // <div class="test">
@endbem         // </div>

@bem( 'block', 'article' ) // <article class="block">
@endbem                    // </article>

@bem( 'block', 'quote', [ 'data-inspiration', 'class' => 'js-action' ] ) // <quote class="js-action block" data-inspiration >
@endbem                                                                  //</quote>

@bem( 'block', [ 'id' => "anchor" ] ) // <div class="block" id="anchor">
@endbem                               // </div>

```