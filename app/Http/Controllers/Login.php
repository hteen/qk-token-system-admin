<?php
/**
 * 后台登陆
 * Created by PhpStorm.
 * Date: 2016/10/8 0008
 * Time: 10:27
 */

namespace App\Http\Controllers;

use App\Model\Manage\Manager;
use App\Model\AdminLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Login extends Base
{
    public function index()
    {


        view()->share('page_name', '用户登录');
        return $this->render(__METHOD__, '用户登录');
    }

    /**验证登陆
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function login(Request $request)
    {
        if (preg_match("/Chrome\/(\d+)/i", $request->userAgent(),$matches)) {
            if($matches[1] < env("MIN_CHROME_VERSION"))
            {
                $this->ajaxError('用户名或密码错误');
            }
        } else {
            $this->ajaxError('用户名或密码错误');
        }

        $rules = [
            'username' => 'required|regex:/^[a-z][a-z0-9_]{3,14}[a-z0-9]$/',
            'password' => 'required',
            'remember' => 'in:0,1'
        ];
        $this->validate($request, $rules);
        $data = $request->all();
        $r = \Auth::attempt(['username' => $data['username'], 'password' => $data['password'], 'status' => 1], isset($data['remember']) && $data['remember'] == 1 ? true : false);

        if($r == true){

            $manager = Manager::find(\Auth::user()->id);
            
            $token = Str::random(60);

            $manager->token = $token;
            $manager->last_ip = $request->ip();
            $manager->last_time = date('Y-m-d H:i:s',time());

            $manager->save();

            $AdminLogin = new AdminLogin();
            $AdminLogin->ip =  $request->ip();
            $AdminLogin->username = $data['username'];
            $AdminLogin->save();

            session()->put('token', $token);
        }

        return $r ? $this->ajaxSuccess('/') : $this->ajaxError('用户名或密码错误');
    }

    /**
     * 注销
     */
    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
}