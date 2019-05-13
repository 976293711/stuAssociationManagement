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
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class RoleController extends BaseController
{
    public function index(){
        $roleData =  Role::all()->sortByDesc('id');
        $res_data = [];
        foreach ($roleData as $role){
            //取单一字段
            $permissionList = $role->permissions->pluck('slug')->toArray();
            $role_array = $role->toArray();
            unset($role_array['permissions']);
            $role_array['menus'] = $permissionList;
            $res_data[] = $role_array;
        }
        return $this->successResponse('Success',$res_data);
    }

    /**
     * 自带分页的表格
     * @param Request $request
     * @return \App\Http\Controllers\StuAdmin\Response
     */
    public function indexPaginator(Request $request){

        $perPage = 20;            //每页显示数量
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }
        $roleData =  Role::all();
        $res_data = [];
        foreach ($roleData as $role){
            //取单一字段
            $permissionList = $role->permissions->pluck('slug')->toArray();
            $role_array = $role->toArray();
            unset($role_array['permissions']);
            $role_array['menus'] = $permissionList;
            $res_data[] = $role_array;
        }
        $item = array_slice($res_data, ($current_page-1)*$perPage, $perPage); //注释1
        $total = count($res_data);
        $paginator =new LengthAwarePaginator($item, $total, $perPage, $current_page);
        return $this->successResponse('Success',$paginator);
    }

    /**
     * 保存角色
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

        $md = new Role();

        if(!isset($params['id'])){
            $isExist = Role::where('slug', $params['slug'])->count();
            if($isExist){
                return $this->failResponse('抱歉，当前角色已存在');
            }
        }

        $md->updateOrCreate(['slug' => $params['slug']], $params);
        return $this->successResponse('store success');
    }

    public function destroy($id){
        $roleManager = new \HuangYi\Rbac\Managers\RoleManager();
        try{
            $roleManager->delete($id);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }

        return $this->successResponse('delete success');
    }

    public function show($id){
        $roleManager = new \HuangYi\Rbac\Managers\RoleManager();
        try{
            $role = $roleManager->find(1);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }

        return $this->successResponse('Success', $role);
    }

    public function bindPermisson(){
        $params = request('params');
        $roleManager = new \HuangYi\Rbac\Managers\RoleManager();

        $permissionIds = [];
        foreach ($params['menus'] as $item) {
            if($item == 'platform_all')
                continue;
            $data = DB::table('permissions')
                ->where('slug', $item)
                ->first();
            $permissionIds[] = $data->id;
        }

        try{
            DB::table('role_permission')
                ->where('role_id', $params['role_id'])
                ->delete();
            $roleManager->attachPermissions($params['role_id'],$permissionIds);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }
        return $this->successResponse('bind success');
    }

    public function removePermission(){
        $params = request('params');
        $roleManager = new \HuangYi\Rbac\Managers\RoleManager();
        try{
            $roleManager->detachPermissions($params['role_id'],$params['ids']);
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }
        return $this->successResponse('bind success');
    }



}