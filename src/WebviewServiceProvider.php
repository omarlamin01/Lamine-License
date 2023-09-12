<?php
namespace Lamine\License;

use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewServiceProvider;

class WebviewServiceProvider  extends ViewServiceProvider
{

    /**
     *  Register View Folder
     *
     * @return void
     */
    public function registerViewFinder()
    {

        $this->app->bind('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];

            return new FileViewFinder($app['files'], array(base_path($paths)));
        });
    }
}