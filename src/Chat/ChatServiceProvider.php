<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/15
 * Time: 13:55
 */
namespace Gongjieqi\Chat;
use Gongjieqi\Command\ChatInitCommand;
use Gongjieqi\Command\ChatStartCommand;
use Illuminate\Support\ServiceProvider;


class ChatServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/chat.php' => config_path('chat.php'),
            __DIR__ . '/lib/start_businessworker.php' => app_path('/server/start_businessworker.php'),
            __DIR__ . '/lib/start_gateway.php' => app_path('/server/start_gateway.php'),
            __DIR__ . '/lib/start_register.php' => app_path('/server/start_register.php'),
            __DIR__ . '/lib/Events.php' => app_path('/server/Events.php'),
            __DIR__ . '/lib/start.php' => app_path('/server/start.php'),
            __DIR__. '/../views/chat/chat.blade.php' => resource_path('views/chat/chat.blade.php'),
            //__DIR__. '/../views/chat/user.blade.php' => resource_path('views/chat/user.blade.php')
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChatInitCommand::class,
            ]);
        }
    }
}