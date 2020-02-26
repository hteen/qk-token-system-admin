<?php namespace App\Service;
use Illuminate\Support\Facades\Redis;

/**
 * service 基类
 * Class BaseService
 * @package App\Service
 */
class BaseService
{
    /**
     * GET方式请求
     * @param string $url url
     * @return mixed $result
     */
    public function getData($url)
    {
        $ch = curl_init();

        // 取消SSL证书检验
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        } else {
            return $result;
        }
    }

    /**
     * POST方式请求
     * @param mixed $data 需要发送的数据
     * @param string $url url
     * @return mixed $result
     */
    public function postData($data, $url)
    {
        $ch = curl_init();
        // 取消SSL证书检验
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo curl_errno($ch) . ':' . curl_error($ch);
            return false;
        } else {
            return $result;
        }
    }

    /**小数小于0.0001并去掉多余0问题
     * @param $num
     * @return string
     */
    public static function float_format($num)
    {

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

}
