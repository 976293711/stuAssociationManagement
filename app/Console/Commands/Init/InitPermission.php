<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/1/28
 * Time: 15:10
 */

namespace App\Console\Commands\Init;
use App\Permission;

use App\Console\Commands\BaseCommands;

class InitPermission extends BaseCommands
{
    protected $signature = 'stu:manages:init:rbac';

    protected $permissionList = [
        [
            'name' => 'ui主分支',
            'slug' => '/ui',
            'description' => 'init'
        ],
        [
            'name' => 'ui-按钮',
            'slug' => '/ui/buttons',
            'description' => 'init',
            'parent_permission' => '/ui',
        ],
        [
            'name' => 'ui-弹框',
            'slug' => '/ui/modals',
            'description' => 'init',
            'parent_permission' => '/ui',
        ],
        [
            'name' => '权限设置',
            'slug' => '/permission',
            'description' => 'init',
        ],

    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->print('开始');
        $this->initPermission();
        $this->print('结束');
    }

    public function initPermission(){
        $this->print('正在创建Permission');
        foreach ($this->permissionList as $item){
            Permission::create($item);
        }
    }


}