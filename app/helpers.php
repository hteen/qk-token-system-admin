<?php

/**
 * 根据起点坐标和终点坐标测距离
 * @param  [array]   $from 	[起点坐标(经纬度),例如:[118.012951,36.810024]]
 * @param  [array]   $to 	[终点坐标(经纬度)]
 * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
 * @param  [int]     $decimal   精度 保留小数位数
 * @return [string]  距离数值
 */
function getDistance($from,$to,$km=false,$decimal=2)
{
    sort($from);
    sort($to);
    $EARTH_RADIUS = 6370.996; // 地球半径系数

    $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

    if ($km) {
        $distance = $distance / 1000;
    }
    return round($distance, $decimal);
}

/**
 * 16进制转10进制
 * @param string $hex
 * @return int|string
 */
function HexDec2(string $hex)
{
    $dec = 0;
    $len = strlen($hex);
    for ($i = 1; $i <= $len; $i++) {
        $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
    }
    return $dec;
}

function getOffset(int $page = 1, int $pageSize = 10): int
{
    return ($page - 1) * $pageSize;
}

/**小数小于0.0001并去掉多余0问题
 * @param $num
 * @return mixed
 */
function float_format($num){
    $num = explode('.', $num);
    if (count($num) == 1) {
        return $num[0];
    }
    $de = $num[1];
    $de = rtrim($de, 0);
    if (strlen($de) > 0) {
        return $num[0] . '.' . $de;
    } else {
        return $num[0];
    }
}

function getClientIp()
{
    return $_SERVER['HTTP_ALI_CDN_REAL_IP']??\Request::getClientIp();
}


/**
 * 获取指定字段的数据，第一个参数传true将返回Collection对象
 * @param type   $data 数组或集合对象或Request
 * @param string $keys 多个Key，支持以单元素数组方式改变Key名称
 */
function onlys($data, ...$keys){
    $toColl = false;
    $arr    = array();

    if($data === true){
        $toColl = true;
        $data   = array_shift($keys);
    }

    if(is_object($data)){
        if($data instanceof \Illuminate\Http\Request){
            $arr = $data->all();
        }else if($data instanceof \Illuminate\Support\Collection){
            $arr = $data->toArray();
        }
    }else if(is_array($data)){
        $arr = $data;
    }

    $keys = array_map(function($keyname)use(&$arr){
        if(is_array($keyname)){
            $key    = array_key_first($keyname);
            $newKey = reset($keyname);

            if($key !== $newKey && array_key_exists($key, $arr)){
                $arr[$newKey] = $arr[$key];
                unset($arr[$key]);
            }
            return $newKey;
        }
        return $keyname;

    }, $keys);

    $arrRes = array_intersect_key($arr, array_flip($keys));

    if($toColl){
        return collect($arrRes);
    }
    return $arrRes;
}


/**
 * 判断空输入，仅支持单值或数组元素判断，第一个参数传true将在数组元素判断时忽略不在数组中的元素
 * @param type   $data 单值
 * @param string $keys 多个数组Key，如果省略表示只判断单值，如果指定则判断数组元素
 */
function empty_input(){
    $ig   = false;
    $keys = func_get_args();
    $data = array_shift($keys);
    
    if($data === true){
        $ig   = true;
        $data = array_shift($keys);
    }
    
    $checks = array();
    
    if($keys){
        if(!is_array($data)){
            return true;
        }
        
        foreach($keys as $key){
            if(array_key_exists($key, $data)){
                $checks[] = $data[$key];
                
            }else if(!$ig){
                return true;
            }
        }
        
    }else{
        $checks[] = $data;
    }
    
    foreach($checks as $check){
        if(!$check && $check !== '0'){
            return true;
        }
    }
    
    return false;
}


/**
 * 清除数组中所有为null的元素
 * @param array $arr 数组引用
 */
function array_clear(&$arr){
    if(!is_array($arr)){
        return $arr;
    }
    
    return $arr = array_filter($arr, function($elem){
        return !is_null($elem);
    });
}