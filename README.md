# Installation

Install via composer with

```shell
$ composer install kagagnon/bem-php
```

## Optional configurations

Configurations are static properties to the `KAGagnon\BemPhp\Helpers\BemHelper` class. You can edit them before using `BemPhp`:

```php
<?php
/**
 * Separator between block and element
 */
KAGagnon\BemPhp\Helpers\BemHelper::$element_separator = '__';

/**
 * Separator between modifer and block/element
 */
KAGagnon\BemPhp\Helpers\BemHelper::$modifier_separator = '--';

/**
 * Should @bem create a tag element
 */
KAGagnon\BemPhp\Helpers\BemHelper::$create_tag = false;

/**
 * If create_tag is true, what's the default tag name
 */
KAGagnon\BemPhp\Helpers\BemHelper::$default_tag = 'div';

/**
 * Prefix of BEM classes.
 */
KAGagnon\BemPhp\Helpers\BemHelper::$block_prefix = '';
```

# How to use

It is recommanded to include a `use` statement before using `BemHelper` Class. Alternatively, you can create helper functions to shorten the use of the class.

## Blocks

You can create a new block with the directive `BemHelper::startBlock( $block_name )`. Once the block if finished,
you can use `BemHelper::endBlock()` to close the block. BEM block can be nested for sub-module. So this:

```php
<?php
BemHelper::startBlock( 'block' );
    BemHelper::startBlock( 'other-block' );
    BemHelper::endBlock();
BemHelper::endBlock();
```

is a valid syntax.

## Classes

To generate a class, you can use `BemHelper::bemClass( [ string|array $element [, string|array $modifiers[, integer $parent_level = 0 ]]] )`.

- Passing no arguments generate the block name.
- Passing a string as first argument generate the block name with an element.
- Passing an array as first argument generate the block name with the modifiers.
- Passing a string and an array generate a block name with an element and its modifiers.
- Passing 2 strings generate a block name with an element and explode the string on spaces to generate the modifiers.
- Parent level integer allow to use a parent BEM class in nested loops.

 Check the examples below:

```php
<?php
BemHelper::startBlock( 'cup' ); // Init Block "cup"
    BemHelper::bemClass(); // Generate : cup
    BemHelper::bemClass( [ 'glass' ] ); // Generate : cup cup--glass

    BemHelper::startBlock( 'spoon' ); // Init Block "spoon"
        BemHelper::bemClass(); // Generate : spoon
        BemHelper::bemClass( [ 'metallic', 'cold' ] ); // Generate : spoon spoon--metallic spoon--cold
        BemHelper::bemClass( 'sugar' ); // Generate : spoon__sugar
        BemHelper::bemClass( 'sugar', 'half-tea-spoon' ); // Generate : spoon__sugar spoon__sugar--half-tea-spoon
        BemHelper::bemClass( 'spoon-holder', '', 1 ) // Generate : cup__spoon-holder
    BemHelper::EndBlock();

    BemHelper::bemClass( 'tea' ); // Generate : cup__tea
    BemHelper::bemClass( 'coffee' ); // Generate : cup_coffee
    BemHelper::bemClass( 'coffee' , 'with-sugar' ); // Generate : cup__coffee cup__coffee--with-sugar
    BemHelper::bemClass( 'coffee' , [ 'with-sugar', 'with-milk'] ); // Generate : cup__coffee cup__coffee--with-sugar cup__coffee--with-milk
    BemHelper::bemClass( 'coffee' , 'with-sugar with-milk no-foam' ); // Generate : cup__coffee cup__coffee--with-sugar cup__coffee--with-milk cup__coffee--no-foam
BemHelper::EndBlock();
```

## HTML example

```php
<?php BemHelper::startBlock( 'article' ) ?>
   <div class="<?php BemHelper::bemClass() ?>">
       <h1 class="<?php BemHelper::bemClass( 'title' ) ?>">Article Name</h1>

       <p class="<?php BemHelper::bemClass( 'content' ) ?>">Article text...</p>

       <?php BemHelper::startBlock( 'meta' ) ?>
           <div class="<?php BemHelper::bemClass() ?>">
               <a href="..." class="<?php BemHelper::bemClass( 'link', 'inactive' ) ?>">0 comments</a>
               <a href="..." class="<?php BemHelper::bemClass( 'link', 'clear danger' ) ?>">Delete</a>
               <a href="..." class="<?php BemHelper::bemClass( 'link' ) ?>">Edit</a>
           </div>
       <?php BemHelper::endBlock(); ?>
   </div>
<?php BemHelper::endBlock(); ?>
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

# Create node with `startBlock()`

You can pass argument to `startBlock()` to automatically generate an HTML tag.
To do so, you can pass the tag name as second argument and, optionally, an array of attributes.

You can also skip the tag name and pass an array as second argument. That will create an HTML element base on the `default_tag` configuration.

Additionally, if you set `create_tag` to true, `startBlock()` will always create a tag base on
the `default_tag` configuration if only 1 argument is passed.

To pass modifiers to the tag, simply pass `_modifiers` in the array: an array for multi-modifiers or a string for single modifier.

## Example

```php
<?php
// We assume `create_tag` is set to true

BemHelper::startBlock( 'block' ) // <div class="block">
BemHelper::endBlock()            // </div>

BemHelper::startBlock( 'block', 'article' ) // <article class="block">
BemHelper::endBlock()                       // </article>

BemHelper::startBlock( 'block', 'quote', [ 'data-inspiration', 'class' => 'js-action' ] ) // <quote class="js-action block" data-inspiration >
BemHelper::endBlock()                                                                     //</quote>

BemHelper::startBlock( 'block', [ 'id' => "anchor" ] ) // <div class="block" id="anchor">
BemHelper::endBlock()                                  // </div>

BemHelper::startBlock( 'block', [ 'id' => "anchor", '_modifiers' => 'modifier' ] ) // <div class="block block--modifier" id="anchor">
BemHelper::endBlock()                                  // </div>

BemHelper::startBlock( 'block', [ '_modifiers' => [ 'modifier1', 'modifier2' ] ] ) // <div class="block block--modifier1 block--modifier2">
BemHelper::endBlock()                                  // </div>

```

# Use with Laravel (Blade directives)

This plugin comes with a Laravel integration. You can include the `KAGagnon\BemPhp\BemServiceProvider` class in your application service providers list. The following directive will be mapped :

```
@bem( ... ) => BemHelper::startBlock
@bemclass( ... ) => BemHelper::bemClass
@endbem => BemHelper::endBlock
```

# Twig extension

This plugin comes with a Twig integration. You can register the `KAGagnon\BemPhp\Twig\BemTwigExtension` in Twig extensions. The following directive will be mapped :

```
{% bem( ... ) %} => BemHelper::startBlock
{{ bemclass( ... ) }} => BemHelper::endBlock
{% endbem %} => BemHelper::getBemClass
```
