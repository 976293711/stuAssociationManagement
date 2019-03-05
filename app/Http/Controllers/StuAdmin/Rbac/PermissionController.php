<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/2/11
 * Time: 15:19
 */

namespace App\Http\Controllers\StuAdmin\Rbac;


use App\Http\Controllers\StuAdmin\BaseController;
use App\Permission;
use App\User;
use HuangYi\Rbac\Managers\PermissionManager;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    /**
     * 列出后台列表配置文件
     * TODO 可根据角色进行分配 待实施
     * @return \App\Http\Controllers\StuAdmin\Response
     */
    public function index(Request $request){

        $menuData = Permission::all()->toArray();
        #找出所有主节点
        $mainNode = [];
        foreach ($menuData as $key => $item){
            if($item['parent_permission'] == null ){
                $mainNode[] = [
                    'id' => $item['id'],
                    'title' => $item['name'],
                    'key' => $item['slug'],
                ];
                unset($menuData[$key]);
            }

        }
        foreach ($mainNode as &$main_item){
            $flag = $main_item['key'];
            foreach($menuData as $item){
                if(strstr($item['slug'], $flag)){
                    $main_item['children'][] = [
                        'id' => $item['id'],
                        'title' => $item['name'],
                        'key' => $item['slug'],
                    ];
                }
            }
        }
        return $this->successResponse('success',$mainNode);
    }


    /**
     * 增加权限
     * @return \App\Http\Controllers\StuAdmin\Response
     */
    public function store(){
        $params = request('params');
        $validator = \Validator::make($params, [
            'name' => 'required|max:8|string',
            'slug' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }


        $permissionManager = new Permission();

        $permissionManager->updateOrCreate(['slug' => $params['slug']], $params);
        return $this->successResponse('store success');
    }

    /**
     * 删除权限
     * @param $id
     * @return \App\Http\Controllers\StuAdmin\Response
     */
    public function destroy($id){
        $permissionManager = new PermissionManager();
        try{
            $permissionManager->delete($id);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }

        return $this->successResponse('delete success');
    }

    public function show($id){
        $permissionManager = new PermissionManager();
        try{
            $permission = $permissionManager->find($id);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }

        return $this->successResponse('Success', $permission);
    }

}