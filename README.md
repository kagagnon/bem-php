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