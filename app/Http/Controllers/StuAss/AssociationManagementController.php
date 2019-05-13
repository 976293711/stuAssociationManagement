<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/23
 * Time: 15:20
 */

namespace App\Http\Controllers\StuAss;


use App\Http\Controllers\StuAdmin\BaseController;
use App\Models\AssociationManagement;
use Qiniu\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Qiniu\Storage\UploadManager;

class AssociationManagementController  extends BaseController
{

    // 用于签名的公钥和私钥
    const AccessKey = 'CAx1Zf2fGl-y4U9J86nh1auYdNQdTKBlNRh_fl5T';
    const SecretKey = 'cUk1vL_Gq5dKWftwxwuhlolSxtpyHatEw87UfPW';

    public function index(){
        $list = AssociationManagement::all();
        return $this->successResponse("获取列表成功", $list);
    }

    public function getAssList(){
        $list = AssociationManagement::where('state', 1)->get();
        return $this->successResponse("获取列表成功", $list);
    }

    public function show($id){
        $info = AssociationManagement::find($id);
        $info->ass_type = AssociationManagement::getConstant( $info->ass_type,'ass_type');
        return $this->successResponse("Success", $info);
    }

    public function store(){
        $params = request('params');
        $validator = \Validator::make($params, [
            'ass_name'              => [
                                        'required',
                                        'string'
                                    ],
            'ass_img'               => 'required',
            'ass_introduction'      => 'required',
            'ass_type'              => 'required',
            'charge_phone'          => 'required',
            'state'                 => 'required',
        ]);

        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->toArray() as $item){
                $msg = $msg.$item[0];
            }
            return $this->failResponse($msg);
        }

        if(isset($params['ass_id'])){
            $res = AssociationManagement::updateOrCreate(['ass_id' => $params['ass_id']],
                [

                    'ass_name'              => $params['ass_name'],
                    'ass_img'               => $params['ass_img'],
                    'ass_introduction'      => $params['ass_introduction'],
                    'ass_type'              => $params['ass_type'],
                    'charge_phone'          => $params['charge_phone']
                ]
            );
            if(!$res){
                return $this->failResponse('store fail');
            }
            return $this->successResponse('Success');
        }

        $res = AssociationManagement::updateOrCreate(['ass_name' => $params['ass_name']],
            [
                'ass_name'              => $params['ass_name'],
                'ass_img'               => $params['ass_img'],
                'ass_introduction'      => $params['ass_introduction'],
                'ass_type'              => $params['ass_type'],
                'charge_phone'          => $params['charge_phone']
            ]
        );
        if(!$res){
            return $this->failResponse('store fail');
        }
        return $this->successResponse('Success');


    }

    public function destroy($id){
        $info = AssociationManagement::find($id);
        $info->state = -1;
        if(!$info->save()) {
            return $this->failResponse('delete fail');
        }
        return $this->successResponse('Success');
    }

    public function uploadImage(Request $request){
        $auth = new Auth(self::AccessKey, self::SecretKey);
        $bucket = 'ass/';


        $file = $request->file;
        $filePath = $file->getRealPath();
        $key = $file->getClientOriginalName();
        $uploadMgr = new UploadManager();
        $uptoken = $auth->uploadToken($bucket, null, 3600);
        list($ret, $err) = $uploadMgr->putFile($uptoken, null, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }

    }


}