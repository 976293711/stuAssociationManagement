<?php
/**
 * Created by PhpStorm.
 * User: wilson
 * Date: 2019/4/24
 * Time: 11:45
 */

namespace App\Http\Controllers\StuAss;


use App\Http\Controllers\StuAdmin\BaseController;
use App\Models\StuAudit;
use App\Models\StuRegistrationManages;
use Illuminate\Validation\Rule;

class AssMemberController extends BaseController
{

    public function getAuditByAssId($AssId){
        $builder = StuRegistrationManages::where('ass_id', $AssId)->with(['associationInfo', 'stuAuditInfo'])->get();
        $stuList = [];
        foreach ($builder as $item ){
            $info = [
                'sid'                   => $item->sid,
                'ass_name'              => $item->associationInfo->ass_name,
                'name'                  => $item->name,
                'number'                => $item->number,
                'sex'                   => $item->sex ,
                'phone'                 => $item->phone,
                'college'               => $item->college,
                'grade'                 => $item->grade,
                'detail'                => $item->detail,
                'department'            => $item->department,
                'created_at'            => $item->created_at,
                'state'                 => $item->stuAuditInfo->state ?? 0,
                'inter_area'            => $item->stuAuditInfo->inter_area ?? ' ',
                'inter_time'            => $item->stuAuditInfo->inter_time ?? ' '
            ];
            $stuList [] = $info;
        }

        return successResponse('Success', $stuList);
    }

    public function saveAudit(){
        $params = request('params');
        selfValidation($params, [
            'sid'           => [
                'required',
                Rule::exists('stu_registration_manages', 'sid'),
            ],
            'state'         => 'required',
        ]);

        $res = StuAudit::updateOrCreate(['sid' => $params['sid']],[
            'sid'           => $params['sid'],
            'state'         => $params['state'],
            'inter_area'    => $params['inter_area'],
            'inter_time'    => $params['inter_time']
        ]);

        if(!$res){
            return $this->failResponse('store fail');
        }
        return $this->successResponse('Success');
    }
}