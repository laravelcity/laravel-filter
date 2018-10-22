<?php

namespace Laravelcity\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot ()
    {
        //lang
        $this->loadTranslationsFrom(__DIR__ . '/Lang/' , 'Filter');

        // views
        $this->loadViewsFrom(__DIR__ . '/Views' , 'Filter');
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/Filter') ,
        ] , 'filter');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register ()
    {
        //configs
        $this->mergeConfigFrom(
            __DIR__ . '/Config/filter.php' , 'filter'
        );
        $this->publishes([
            __DIR__ . '/Config/filter.php' => config_path('filter.php') ,
        ] , 'filter');

        // publish lang
        $this->publishes([
            __DIR__ . '/Lang/' => resource_path('lang/vendor/filter') ,
        ]);

        $models = config('filter.models');
        if (count($models) > 0) {

            foreach ($models as $model) {
                $this->app->when($model['filter'])->needs(Builder::class)->give(function () use ($model) {
                    return app($model['query']['model'])->with($model['query']['with'])->select();
                });
            }
        }

    }

    public function provides ()
    {
        $models = config('filter.models');
        $modelsName = [];

        if (count($models) > 0) {
            foreach ($models as $model)
                $modelsName[] = @$model['filter'];
        }
        return $modelsName;
    }
}
