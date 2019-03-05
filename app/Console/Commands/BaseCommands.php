<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/1/28
 * Time: 15:12
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
class BaseCommands extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $signature = 'stu:manages:base';

    protected function print($msg){
        $this->info(date('Y-m-d H:i:s').":".get_class($this).":".$msg);
    }

}