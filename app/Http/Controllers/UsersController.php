<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Users;


class UsersController extends Base
{
    protected $model;
    protected $logic;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Users();
    }

    /**会员列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->render('User::index', '用户列表');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function page(Request $request)
    {
        $limit = \Request::input('limit') ? (int)\Request::input('limit') : 20;
        $offset = \Request::input('offset') ? (int)\Request::input('offset') : 0;

        $where = function ($query) use ($request) {
            $uid_invite = $request->input('uid_invite')??false;
            $valid = $request->input('valid');

            if(isset($uid_invite) && $uid_invite!=false){

                $query = $query->where('invite_uid', $uid_invite);
            }
            if($valid==1){
                $query = $query->where('task_effect', '>',0);

            }elseif($valid==2){
                $query = $query->where('task_effect', '<=',0);
            }

            return $query;
        };

        //要查询的字段，查所有字段传['*']
        $fields = ['*'];
        $data =  $this->model->getPaginateDataAjax($fields, $where, null, $limit, $offset);
        $result = [];
        foreach ($data as $v) {
            if (!empty($v->risk_tag)){
                $tagArr = explode(',', $v->risk_tag);
                foreach ($tagArr as &$x){
                    $x = Users::$tags[$x];
                }
                $v->risk_tag_name = implode('，', $tagArr);
            }
            //处理身份证
            if ( $v["UserCard"] ) {
                $cardInfo = parse_id_card($v["UserCard"]["id_card"]);
                $v["age"] = @$cardInfo["age"];
                $v["gender"] = @$cardInfo["gender"];
            } else {
                $v["age"] = "";
                $v["gender"] = "";
            }
            $v->status_name = Users::$status_label[$v->status];

            if ($v->register_ip) {
                $addressArray = IP::find($v->register_ip);
                //var_dump($addressArray);
                $addressString = '';
                if ($addressArray[0]) {
                    $addressString .= $addressArray[0] . ' ';
                }
                if ($addressArray[1]) {
                    $addressString .= $addressArray[1] . ' ';
                }
                if ($addressArray[2]) {
                    $addressString .= $addressArray[2] . ' ';
                }

                $v->register_ip_address =  trim($addressString);
            }

            if ($v->login_ip) {
                $addressArray = IP::find($v->login_ip);
                //var_dump($addressArray);
                $addressString = '';
                if ($addressArray[0]) {
                    $addressString .= $addressArray[0] . ' ';
                }
                if ($addressArray[1]) {
                    $addressString .= $addressArray[1] . ' ';
                }
                if ($addressArray[2]) {
                    $addressString .= $addressArray[2] . ' ';
                }

                $v->login_ip_address =  trim($addressString);
            }
            
            $result[] = $v;
        }

        return response()->json(['data' => $result, 'total' => $data->total(), 'where'=>$where]);
    }


    /**禁用账号
     * @param Request $request
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function disable(Request $request)
    {
        $uid =$request->input('uid');
        $status =$request->input('status');

        try {
            $user = $this->model->find($uid);
            $user->status = $status;
            $user->save();
            
            return $this->ajaxSuccess();
            
        } catch (\Exception $e) {

            return $this->ajaxError($e->getMessage());
        }
    }


}

