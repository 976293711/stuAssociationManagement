<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/23
 * Time: 18:44
 */

namespace App\Http\Controllers\StuAss;


use App\Http\Controllers\StuAdmin\BaseController;
use App\Models\AssNotice;
use Illuminate\Validation\Rule;

class AssNoticeController extends BaseController
{
    const DEFAULT_MD_CONTENT = '#### 抱歉 没有查询到相关公告 请负责人进行填写';

    public function getNoticeByAssID($AssId){
        $builder =  AssNotice::find($AssId);
        if(!$builder){

            $info = [
              'ass_id'          => $AssId,
              'ass_notice'      => self::DEFAULT_MD_CONTENT
            ];
            return $this->successResponse('Success', $info);
        }
        $info = [
            'ass_id'          => $builder->ass_id,
            'ass_notice'      => $builder->ass_notice
        ];
        return $this->successResponse('Success', $info);
    }

    public function saveNotice(){
        $params = request('params');
        $validator = \Validator::make($params, [
            'ass_id'        => [
                'required',
                Rule::exists('association_manages', 'ass_id'),
            ],
            'ass_notice'    => 'required',
        ]);
        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->toArray() as $item){
                $msg = $msg.$item[0];
            }
            return $this->failResponse($msg);
        }

        $builder = AssNotice::updateOrCreate(['ass_id' => $params['ass_id']], [
            'ass_id'                    => $params['ass_id'],
            'ass_notice'                 => $params['ass_notice'],
            ]);

        if(!$builder){
            return $this->failResponse('store fail');
        }
        return $this->successResponse('Success');
    }
}