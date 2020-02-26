<?php namespace App\Libs\Manager;

use App\Model\Manage\ManagerList as model;
use App\Model\Manage\Nodes;
use App\Model\Manage\Role;
use Illuminate\Support\Str;

/**
 * 管理员相关
 * Date: 2016/10/17 0017
 * Time: 11:15
 */
class Manager
{
    private $error_message;

    public function getError()
    {
        return $this->error_message;
    }

    /**
     * 创建管理员
     * @param string $username
     * @param string $password
     * @param string $cn_name
     * @param int $type
     * @return mixed
     */
    public function create(string $username, string $password, string $cn_name)
    {
        $salt = $this->getSalt();
        $password = $this->getPassword($password, $salt);
        return model::create(['username' => $username, 'password' => $password, 'salt' => $salt, 'cn_name' => $cn_name]);
    }

    /**
     * 生成加密盐
     */
    protected function getSalt(): string
    {
        return Str::random(8);
    }

    /**
     * 生成加密后的密码
     * @param string $password
     * @param string $salt
     * @param bool $encode
     * @return string
     */
    protected function getPassword(string $password, string $salt, bool $encode = true): string
    {
        $password = $encode ? sha256hex($password) : $password;
        $password .= $salt;
        return \Hash::make($password);
    }

    /**
     * 修改密码
     * @param model $manager
     * @param string $password
     * @param string $old_password
     * @return bool
     */
    public function changePassword(model $manager, string $password, string $old_password): bool
    {
        $old_password = $this->getPassword($old_password, $manager->salt);
        if ($old_password != $manager->getAuthPassword()) {
            $this->error_message = '旧密码不正确，请核对';
            return false;
        }
        $password = $this->getPassword($password, $manager->salt);
        $manager->password = $password;
        return $manager->save();
    }

    /**
     * 编辑
     * @param array $data
     * @return mixed
     */
    public function edit(array $data)
    {
        unset($data['username']);
        $manager = model::find($data['id']);
        if ($data['password']) {
            $data['password'] = $this->getPassword($data['password'], $manager->salt);
        } else {
            unset($data['password']);
        }
        return $manager->fill($data)->save();
    }

    public function checkPower($uri = null)
    {
        if (!$uri) {
            $uri = \Route::current()->uri();
            if ($uri == '/' || !$uri) {
                return true;
            }
        }
        if ($uri == 'manage/node/load'){
            return true;
        }
        if (!auth()->user()) {
            return false;
        }

        if (auth()->user()->id == 1) {
            return true;
        }

        $all = explode('/', $uri);
        $first = $all[0];
        $power = explode(',', auth()->user()->power);
        $menus = Nodes::cachedNodes()->where('hide', 1)->where('level', '<', 3)->pluck('uri');
        if (in_array($first, $power)){
            if ((count($all)==1 || count($all)==2) && !empty($all[1])){
                if (!$menus->contains($first.'/'.$all[1])){
                    return true;
                }
                if (in_array($first.'/'.$all[1], $power)){
                    return true;
                }
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

}