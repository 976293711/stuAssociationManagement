<?php


namespace App\Http\Controllers\StuAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function getPostParams(array $default = [], Request $request){
//        $request = new Request();
        $params= $request->toArray();
        var_dump($params);
        if (!is_array($params)) {
            $params = [];
        }
        return array_merge($default, $params);
    }

    /**
     * 成功返回
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Response
     */
    public function successResponse($msg='',$data=[],$code=1){
        $result=[
            'state'=>true,
            'msg'=>$msg,
            'code'=>$code,
            'data'=>$data
        ];
        return response()->json($result);
    }

    /**
     * 失败返回
     * @param string $msg
     * @param array $data
     * @param int $code
     * @return Response
     */
    public function failResponse($msg='',$data=[],$code=-1){
        $result= [
            'state'=>false,
            'msg'=>$msg,
            'code'=>$code,
            'data'=>$data
        ];
        return response()->json($result);
    }


}