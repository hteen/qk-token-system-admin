<?php
if (!function_exists('no_repeat_token')) {
    /**
     * 获取防重复提交token
     * @return string
     */
    function no_repeat_token(): string
    {
        return app(\App\Libs\NoRepeated::class, [app('session'), new \Illuminate\Support\Str()])->getToken();
    }
}

if (!function_exists('check_repeat')) {
    /**
     * 验证防重复提交token
     * @return bool
     */
    function check_repeat(): bool
    {
        $manager = app(\App\Libs\NoRepeated::class);
        $token = request($manager::INPUT_NAME);
        return $manager->check($token);
    }
}

if (!function_exists('repeat_field')) {
    /**
     * 防重复表单html
     * @return string
     */
    function repeat_field(): string
    {
        $manager = app(\App\Libs\NoRepeated::class);
        return $manager->getField();
    }
}

if (!function_exists('repeat_refresh')) {
    /**
     * 防重复表单token刷新
     * @param string|null $old
     * @return string
     */
    function repeat_refresh(string $old = null): string
    {
        $manager = app(\App\Libs\NoRepeated::class);
        return $manager->refresh($old);
    }
}

if (!function_exists('vd')) {
    /**
     * 断点调试
     * @param $data
     * @throws \App\Exceptions\AjaxExceptions
     */
    function vd($data)
    {
        var_export($data);
        throw new \App\Exceptions\AjaxExceptions("console", 500);
    }
}

if (!function_exists('current_url')) {
    /**
     * 当前完整url
     */
    function current_url(array $except = null): string
    {
        $prams = $except ? \Request::except($except) : \Request::all();
        $url = Request::url();
        $url .= '?';
        foreach ($prams as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $vv) {
                    $url .= '&' . $k . '[]=' . $vv;
                }
            } else {
                $url .= '&' . $k . '=' . $v;
            }
        }
        return $url;
    }
}

if (!function_exists('get_uri')) {
    /**
     * 当前uri
     */
    function get_uri(): string
    {
        $uri = \Request::path();
        return '/' . trim(str_replace('\\', '/', str_replace(strtolower(config('app.url')), '', strtolower($uri))), '/');
    }
}

if (!function_exists('multi_sort')) {
    function multi_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }
}
if (!function_exists('load_common_config')) {
    /**
     * 加载公共配置
     * @param string $name
     * @return mixed
     */
    function load_common_config(string $name)
    {
        return require_once base_path() . '../../common/config/' . $name . '.php';
    }
}

if (!function_exists('sort_array')) {
    function sort_array(&$array, $key, $order = 'asc', $type = 'number')
    {
        if (is_array($array)) {
            foreach ($array as $val) {
                $order_arr[] = $val[$key];
            }
            $order = ($order == 'asc') ? SORT_ASC : SORT_DESC;
            $type = ($type == 'number') ? SORT_NUMERIC : SORT_STRING;
            array_multisort($order_arr, $order, $type, $array);
        }
    }
}
/**
 * 提交post 数据(array)
 */
if (!function_exists('php_post')) {
    function php_post($url, array $post)
    {
        $post = http_build_query($post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, "Content-type: application/x-www-form-urlencoded");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}
/**
 * 提交get 数据(array)
 */
if (!function_exists('php_get')) {
    function php_get($url, array $post)
    {
        $url = $url . '?' . http_build_query($post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}
/**
 * 提交post json数据
 */
if (!function_exists('php_post_json')) {
    function php_post_json($url, $post = null, $un_escape = false)
    {
        if (is_array($post)) {
            if ($un_escape) {
                $post = json_encode($post, JSON_UNESCAPED_UNICODE);
            } else {
                $post = json_encode($post);
            }
        }
        $content_length = strlen($post);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    "Content-type: application/json\r\n" .
                    "Content-length: $content_length\r\n",
                'content' => $post
            )
        );
        return file_get_contents($url, false, stream_context_create($options));
    }
}

function toXml(array $data)
{
    $xml = "<xml>";
    foreach ($data as $key => $val) {
        if (is_array($val)) {
            $xml .= "<" . $key . ">" . toXml($val) . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
    }
    $xml .= "</xml>";
    return $xml;
}

function xml_post($xml, $url)
{
    $header[] = "Content-type: text/xml";      //定义content-type为xml,注意是数组
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return xml2array($response);
}

function xml2array($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}


if (!function_exists('thumb')) {
    /**
     * 生成缩略图链接
     * @param string $url
     * @param int $width
     * @param int $height
     * @return string
     */
    function thumb(string $url, int $width, int $height): string
    {
        $data = pathinfo($url);
        $filename = $data['filename'];
        $filename .= '_resize-' . $width . 'x' . $height;
        return config('file.domain') . $data['dirname'] . '/' . $filename . '.' . $data['extension'];
    }
}

/**
 * 获取ip
 * @return array|false|string
 */
function getIp()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                $ip = getenv("REMOTE_ADDR");
            else
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                    $ip = $_SERVER['REMOTE_ADDR'];
                else
                    $ip = "unknown";
    return ($ip);
}

/**
 * 根据ip获取地址
 * @param string $ip
 * @return bool|null|string
 */
function getLocation(string $ip)
{
    if (!$ip) {
        return null;
    }
    $str = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    $str = str_replace('var remote_ip_info = ', '', $str);
    $str = str_replace('};', '}', $str);
    $data = json_decode($str, true);
    if ($data['ret'] < 0) {
        return false;
    }
    if ($data['country'] == '中国') {
        if ($data['province'] == $data['city']) {
            $area = $data['province'];
        } else {
            $area = $data['province'] . '-' . $data['city'];
        }

    } else {
        $area = $data['country'] . '-' . $data['province'] . '-' . $data['city'];
    }
    return $area;
}

/**
 * 隐藏手机号
 * @param string $mobile
 * @return string
 */
function hide_mobile(string $mobile): string
{
    $s = substr($mobile, 0, 3);
    $e = substr($mobile, -4);
    return $s . '****' . $e;
}

/**
 * sha256加密
 * @param $str
 * @return string
 */
function sha256hex($str)
{
    $re = hash('sha256', $str, true);
    return bin2hex($re);
}
