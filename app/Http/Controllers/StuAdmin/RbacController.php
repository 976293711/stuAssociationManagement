<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/1/24
 * Time: 18:05
 */

namespace App\Http\Controllers\StuAdmin;
use HuangYi\Rbac\Managers\PermissionManager;

class RbacController extends  BaseController
{
    public function createPermission(){
        $permissionManager = new PermissionManager();

        $permission = $permissionManager->create([
            'name' => 'Create product',
            'slug' => 'product.create',
            'description' => 'Create a new product.',
        ]);

    }

    public function createRole(){
       //TODO
    }

    public function creeteUser(){
        //TODO
    }

    public function show(){
//        echo "123";
        return view('test', ['name' => 'James']);
    }



}