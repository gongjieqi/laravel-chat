<?php

namespace Gongjieqi\Command;

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ChatInitCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'chat:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users and admins uuid create';
   
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->handle();
    }

    /**
     * Execute the console command for Laravel 5.5+.
     *
     * @return void
     */
    public function handle()
    {
        $userModel =  Config('chat.user');

        $adminModel =  Config('chat.admin');

        $user = new $userModel;

        $admin = new $adminModel;

        $usercount = $user::all();

        $admincount = $admin::all();

        $bar = $this->output->createProgressBar(count($usercount)+ count($admincount));

        $user->chunk(100, function ($result) use ($bar) {
            foreach ($result as $item) {
                //
                $item->uuid = Str::random(80);
                $item->save();
                $bar->advance();
            }
        });

        $admin->chunk(100, function ($result) use ($bar) {
            foreach ($result as $item) {
                //
                $item->uuid = Str::random(80);
                $item->save();
                $bar->advance();
            }
        });

        $bar->finish();
    }
}
