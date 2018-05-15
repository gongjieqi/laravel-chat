<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/15
 * Time: 13:55
 */
namespace Gongjieqi\Chat;
use Gongjieqi\Command\ChatInitCommand;
use Illuminate\Support\ServiceProvider;


class ChatServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/chat.php' => config_path('chat.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ChatInitCommand::class,
            ]);
        }
    }
}