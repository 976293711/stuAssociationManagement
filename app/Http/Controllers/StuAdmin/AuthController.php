<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/1/31
 * Time: 17:09
 */

namespace App\Http\Controllers\StuAdmin;
use App\Permission;
use App\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class AuthController extends BaseController
{
    public function test(){
        $pramas = request('params');
        $id = request('id');
        dd($id);
    }

    /**
     * 数组去重
     * @param $array2D
     * @return array
     */
    function array_unique_fb($array2D){

        if($array2D == []){
            return [];
        }

        foreach ($array2D as $v){
            unset($v['pivot']);
            $v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[]=$v;
        }

        $temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组


        foreach ($temp as $k => $v){
            $temp[$k]=explode(',',$v); //再将拆开的数组重新组装
        }
        return $temp;
    }

    public function checkUserPermission(Request $request){
        $permissionSlug = $request->input('slug',null);
        $userInfo = $request->session()->get('user');
        $user_id = $userInfo['user_id'];
        $user = \App\User::find($user_id);
        $hasPermission =  $user->hasPermission($permissionSlug);
        if(!$hasPermission){
           return $this->failResponse('sorry, 当前用户没有权限，请联系管理员');
        }
        return $this->successResponse('Success');
    }

    public function getMenuList(Request $request){
        $userInfo = $request->session()->get('user');
        $user_id = $userInfo['user_id'];
        if($user_id == null){
            return $this->successResponse('null');
        }
        $role = User::find($user_id)->roles;
        $menuData = [];
        foreach ($role as $item){
            $tmp = $item->permissions->toArray();
            $menuData = array_merge($menuData, $tmp);
        }
        $menuData = self::array_unique_fb($menuData);
//        dd($menuData);
        #找出所有主节点
        $mainNode = [];
        foreach ($menuData as $key => $item){
            if($item['4'] == null ){
                $mainNode[] = [
                    'title' => $item['1'],
                    'key' => $item['2'],
                ];
                unset($menuData[$key]);
            }

        }
        foreach ($mainNode as &$main_item){
            $flag = $main_item['key'];
            foreach($menuData as $item){
                if(strstr($item['2'], $flag)){
                    $main_item['children'][] = [
                        'title' => $item['1'],
                        'key' => $item['2'],
                    ];
                }
            }
        }
        return $this->successResponse('success',$mainNode);
    }
}