<?php

namespace Gouyuwang\IMessage;


use Gouyuwang\IMessage\Consoles\GenerateClientIdCommand;
use Gouyuwang\IMessage\Supports\Libraries\Client;
use Gouyuwang\IMessage\Providers\TokenProvider;
use Gouyuwang\IMessage\Supports\Requesters\Requester;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register service
     *
     * @return void
     */
    public function register()
    {
        $source = realpath(__DIR__ . '/../config/config.php');

        if ($this->app instanceof LaravelApplication) {
            if ($this->app->runningInConsole()) {
                $this->publishes([$source => config_path('imessage.php')], 'imessage');
            }
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('imessage');
        }

        $this->mergeConfigFrom($source, 'imessage');

        $this->registerClient();

        $this->registerCommand();
    }

    /**
     * Register command
     *
     * @return void
     */
    protected function registerCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->app->singleton('imessage.cmd.id', function () {
                return new GenerateClientIdCommand;
            });

            $this->commands(['imessage.cmd.id']);
        }
    }

    /**
     * Register client
     *
     * @return void
     */
    protected function registerClient()
    {
        $this->app->singleton("imessage.client", function ($app) {
            $host = $app['config']->get('imessage.host', 'http://127.0.0.1:3003');
            $credentials = $app['config']->get('imessage.credentials', []);
            $master = $app['config']->get('imessage.master', 'master');
            $id = $app['config']->get('imessage.credentials._id', 'randomid');

            $requester = new Requester($host);
            $client = new Client($requester, $master, $id . '-' . md5(gethostname()));
            return $client
                ->setTokenProvider(new TokenProvider($requester))
                ->setCredentials($credentials);
        });
    }

    /**
     * ServiceProvider Boot
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication) {
            require __DIR__ . '/Routes/laravel.php';
        } elseif ($this->app instanceof LumenApplication) {
            ($router = property_exists($this->app, 'router') ? $this->app->router : $this->app)
            && $router->group(['namespace' => 'Gouyuwang\IMessage'], function ($app) {
                require __DIR__ . '/Routes/lumen.php';
            });
        }
    }
}
