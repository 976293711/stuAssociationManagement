<?php

/**
 * 分页帮助函数
 *
 * @param int $page
 * @param int $length
 * @return array
 */
function pager(int $page = 1, int $length = 20)
{
    $start = ($page - 1) * $length;
    return [
        'start'  => $start,
        'length' => $length,
    ];

}

/**
 * 成功返回
 *
 * @param string $msg
 * @param array $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function successResponse(string $msg = '', $data = [], int $code = 1)
{

    return response()->json([
        'msg'   => $msg,
        'data'  => $data,
        'code'  => $code,
        'state' => true,
    ]);
}

/**
 * 失败返回
 *
 * @param string $msg
 * @param int $code
 * @param array $data
 * @return \Illuminate\Http\JsonResponse
 */
function failResponse(string $msg = '', int $code = -1, $data = [])
{
    $result= [
        'state'=>false,
        'msg'=>$msg,
        'code'=>$code,
        'data'=>$data
    ];
    return response()->json($result);
}

/**
 * 生成随机字符串
 *
 * @param $length
 * @return bool|string
 */
function createRandomStr($length)
{
    $str       = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    $randomStr = substr(str_shuffle($str), 0, $length);
    return $randomStr;
}

/**
 * 获取签名
 * @param $timestamp
 * @param $key
 * @return string
 */
function getSignature($timestamp, $key)
{

    $tmpArr = array($timestamp, $key);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode($tmpArr);
    $tmpStr = sha1($tmpStr);

    return $tmpStr;
}

function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
    if(is_array($arrays)){
        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return $arrays;
}

function urandom($len){
    $fp = @fopen('/dev/urandom', 'rb');
    $result = '';
    if ($fp !== FALSE) {
        $result .= @fread($fp, $len);
        @fclose($fp);
    } else {
        trigger_error('Can not open /dev/urandom.');
    }

    // convert from binary to string
    $result = base64_encode($result);
    // remove none url chars
    $result = strtr($result, '+/', '-_');
    return substr($result, 0, $len);
}

function selfValidation($params ,$validation){
    $validator = \Validator::make($params, $validation);
    if ($validator->fails()) {
        $msg = '';
        foreach ($validator->errors()->toArray() as $item){
            $msg = $msg.$item[0];
        }

        $result= [
            'state'=>false,
            'msg'=>$msg,
            'code'=>-1,
            'data'=>''
        ];
        exit(json_encode($result));
    }
}
