<?php namespace App\Libs;

use Illuminate\Support\Str;

/**
 * 防止重复提交表单
 * Date: 2016/11/10 0010
 * Time: 8:49
 */
class NoRepeated
{
    const SESSION_NAME = 'no_repeat_session';
    const INPUT_NAME = '__no_repeat';

    protected function getSessionName()
    {
        return md5(microtime(true) . Str::random() . self::SESSION_NAME);
    }

    /**
     * 获取token
     * @param string|null $session_name
     * @return string
     */
    public function getToken(string $session_name = null):string
    {
        $session_name = $session_name ? $session_name : $this->getSessionName();
        $session = \Session::get($session_name);
        if (empty($session)) {
            $session = $this->rebuilt($session_name);
        }
        return $session;
    }

    /**
     * 获取表单输入框
     */
    public function getField():string
    {
        $session_name = $this->getSessionName();
        $html = '<input type="hidden" name="%s" value="%s" />';
        $token = $session_name . '_' . $this->getToken($session_name);
        $html = sprintf($html, self::INPUT_NAME, $token);
        return $html;
    }

    /**
     * 刷新token值
     * @param string|null $old
     * @return string
     */
    public function refresh(string $old = null):string
    {
        if (!empty($old)) {
            $old = explode('_', $old)[0];
        }
        $session_name = $this->getSessionName();
        return $session_name . '_' . $this->rebuilt($session_name, $old);
    }

    /**
     * 验证
     * @param string $input
     * @return bool
     */
    public function check(string $input = null):bool
    {
        $no_check = \Request::input('__no_check_repeat');
        if(!empty($no_check)){
            return true;
        }
        $token = null;
        if (!empty($input)) {
            $data = explode('_', $input);
            $token = $this->getToken($data[0]);
            $this->rebuilt($this->getSessionName(), $data[0]);
            return $data[1] === $token ? true : false;
        } else {
            $this->rebuilt($this->getSessionName());
            return false;
        }
    }

    /**
     * 重建token
     * @param string $session_name
     * @param string|null $old_session_name
     * @return string
     */
    protected function rebuilt(string $session_name, string $old_session_name = null):string
    {
        if ($old_session_name) {
            \Session::forget($old_session_name);
        }
        $token = md5(microtime() . Str::random());
        \Session::put($session_name, $token);
        \Session::save();
        return $token;
    }
}