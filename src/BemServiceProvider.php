<?php
namespace KAGagnon\BemPhp;


use Illuminate\Support\ServiceProvider;
use KAGagnon\BemPhp\Laravel\Bem;

class BemServiceProvider extends ServiceProvider{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(){
        // publish config file
        $this->publishes([__DIR__.'/../config' => config_path()], 'config');
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/../config/bem-php.php',
            'bem-php'
        );

        BEM::boot();
    }
}
