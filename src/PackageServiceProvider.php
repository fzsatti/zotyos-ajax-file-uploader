<?php

namespace Zotyo\AjaxFileUploader;

use Illuminate\Support\ServiceProvider;
use Validator;
use Route;
use Input;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('FileUploadedInCurrentSession', 'Zotyo\\AjaxFileUploader\\UploadedFileValidator@fileUploadedInCurrentSession');

        $this->publishes([
            __DIR__.'/assets' => public_path('zotyo/ajax-file-uploader'),
            ], 'public');

        $this->publishes([
            $this->getMyConfigPath() => config_path(self::getMyConfigName().'.php'),
            ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->getMyConfigPath(), self::getMyConfigName()
        );

        foreach (self::config('routes') as $route) {
            Route::post($route['url'], [
                'as'   => $route['alias'],
                'uses' => function() use($route) {
                    return self::uploadAction($route['rule']);
                }
            ]);
        }
    }

    private function getMyConfigPath()
    {
        return __DIR__.'/config/config.php';
    }

    private static function getMyConfigName()
    {
        return 'file-uploader';
    }

    public static function config($key)
    {
        return config(self::getMyConfigName().'.'.$key);
    }

    private static function uploadAction($rule)
    {
        $fileName   = self::config('file-name');
        $fileObject = Input::file($fileName);

        $validator = Validator::make([ 'file' => $fileObject], [ 'file' => $rule]);

        if ($validator->fails()) {
            return response()->json([
                    'error' => $validator->errors()->first('file')
                    ], 400);
        }

        $file = File::store($fileObject);

        return response()->json($file->toArray());
    }

}
