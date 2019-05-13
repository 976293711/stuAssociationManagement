<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/2/11
 * Time: 15:19
 */

namespace App\Http\Controllers\StuAdmin\Rbac;


use App\Http\Controllers\StuAdmin\BaseController;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{
    const BASE_ROLE_ID = 1;
    /**
     * 获取用户列表
     */
    public function index(){
        $list = User::all();
        return $this->successResponse("Success", $list);
    }

    public function destroy($id){
        $userManager = User::find($id);
        try{
            $userManager->delete();
        }catch(\Exception $e){
            return $this->failResponse('record not found');
        }

        return $this->successResponse('delete success');
    }

    public function checkUser(Request $request){
        $userInfo = $request->session()->get('user');
        if($userInfo == null){
            return $this->failResponse("暂无当前用户信息，请重新登陆");
        }
        return $this->successResponse("Success", $userInfo);
    }

    public function getUserList(){
        $params = request('params');
        $validator = \Validator::make($params, [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        #获取全部用户数据
        $userData = User::all();
        $lists = [];
        foreach ($userData as $item){
            $role_list = $item->roles->pluck('id')->toArray();
            $key = array_search($params['id'], $role_list );
            if(is_bool($key) && $key==false){
                $lists[] = [
                    'status' => 0,
                    'user_id' => $item['id'],
                    'user_name' => $item['name']
                ];
            }else{
                $lists[] = [
                    'status' => 1,
                    'user_id' => $item['id'],
                    'user_name' => $item['name']
                ];
            }
        }
        return $this->successResponse("Success", $lists);
    }

    /**
     * 用户注册
     * @return \App\Http\Controllers\StuAdmin\Response
     */
    public function store(Request $request){
        $params = request('params');

        $rules = [
            'name' => [
                'required',
                'string',
                Rule::unique('users', 'name'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'password' => 'required'
        ];

        $messages = [
            'email.unique' => '邮箱已存在',
            'name.unique'  => '用户名已存在'
        ];

        try{
            $this->validate($request, $rules, $messages);
        }catch (\Exception $e){
            return $this->failResponse('Error '.$e->getMessage());
        }

//        $validator = \Validator::make($params, [
//            'name' => 'required|string',
//            'email' => 'required',
//            'password' => 'required'
//        ]);
//        if ($validator->fails()) {
//            return $this->failResponse($validator->errors());
//        }

        $is_exists = DB::table('users')
            ->where('email', $params['email'])
            ->first();
        if($is_exists != null){
            return $this->failResponse("当前邮箱已注册");
        }

        $user =  User::saveUser($params);
        $user->attachRoles(self::BASE_ROLE_ID);
        return $this->successResponse('store success');
    }

    public function bindRole(){
        $params = request('params');
        $validator = \Validator::make($params, [
            'role_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }
        #删除记录
        DB::table('user_role')
            ->where('role_id', $params['role_id'])
            ->delete();
        foreach ($params['user_ids'] as $item){
            $user = \App\User::find($item);
            if($user == null){
                continue;
            }
            $user->attachRoles($params['role_id']);
        }

        return $this->successResponse('Success');
    }

    public function removeRole(){
        $user = \App\User::find(1);
        // 解绑一个角色
        $user->detachRoles(1);
        // 同时解绑多个角色
        $user->detachRoles([1, 2, 3]);
    }

    public function logout(Request $request){
        $request->session()->forget('user');
        return $this->successResponse("清除用户成功");
    }

    public function login(Request $request){
        $params = request('params');
        $validator = \Validator::make($params, [
            'email' => 'required|string',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->failResponse($validator->errors());
        }

        $accoutData =  DB::table('users')
            ->where('email', $params['email'])
            ->first();
        if($accoutData == null) {
            return $this->failResponse("Sorry,帐号不存在");
        }
        $pwd_hash = $accoutData->password;
        $data = [
            'user_id' => $accoutData->id,
            'email' => $accoutData->email,
            'name'  => $accoutData->name,
            'remember_token' => $accoutData->remember_token
        ];
        #登陆成功之后的操作
        if (Hash::check($params['password'], $pwd_hash)) {
            #使用session
            $request->session()->put('user',$data);
            return $this->successResponse("登陆成功");
        }else{
            return $this->failResponse("账号或密码错误");
        }

    }

    public function checkRole(){
        $user = \App\User::find(1);
//        $a = $user->hasRole('admin');
//        $b = $user->hasPermission('/ui');
//        dd($b);

        $userPermissions = [];
        foreach ( $user->roles as &$role ) {
            $rolePermissions = $role->permissions->pluck('slug')->toArray();
            $userPermissions = array_merge($userPermissions, $rolePermissions);

        }
        dd($userPermissions);

    }

    public function test(){
        echo phpinfo();
    }

}