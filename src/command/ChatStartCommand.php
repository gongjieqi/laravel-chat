<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/16
 * Time: 8:51
 */

namespace Gongjieqi\Command;

use Illuminate\Console\Command;
use Workerman\Worker;

class ChatStartCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'chat:bin {start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'message server start or stop';

    public function handle()
    {
       $command = $this->argument('start');

       system('php '.__DIR__.'/start.php '.$command,$result);

       print $result;
    }
}