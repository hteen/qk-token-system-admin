<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Assets;


/**
 * 资产管理
 */
class AssetsController extends Base{
    
    
    /**
     * 列表页
     * @param Request $request 参数
     */
    public function index(Request $request){
        
        return $this->render('Assets::index', '资产列表');
    }
    
    
    /**
     * 列表数据
     * @param Request $request 参数
     */
    public function lists(Request $request){
        
        $data  = Assets::getList($request);
        $total = Assets::getList($request, true);
        
        return $this->ret(0, null, false, array(
            'data'  => $data,
            'total' => $total
        ));
    }
    
    
    /**
     * 保存
     * @param Request $request 参数
     */
    public function save(Request $request){
        
        if(!Assets::saveData($request, $request->input('id'))){
            return $this->ret(1, Assets::$err);
        }
        return $this->ret();
    }
    
    
    /**
     * 删除
     * @param Request $request 参数
     */
    public function del(Request $request){
        
        if(!Assets::del($request->input('id'))){
            return $this->ret(1, Assets::$err);
        }
        return $this->ret();
    }
    
    
    /**
     * 自动适应返回方式
     * @param int    $code 返回代码
     * @param string $msg  返回消息
     * @param string $uri  跳转式需要用到的URI，传入true将返回上一个页面
     * @param array  $data 返回数据，支持并行加字段
     */
    private function ret($code = 0, $msg = null, $uri = false, $data = array()){
        
        if(!is_string($msg)){
            $msg = $code ? '操作失败' : '操作成功';
        }
        
        if($uri){
            if($uri === true){
                $redirect = redirect(\URL::previous());
            }else{
                $redirect = redirect($uri);
            }
            
            if(!$code){
                $msg = "#$msg";
            }
            return $redirect->withErrors($msg);
        }
        
        $json = array('code' => $code, 'msg' => $msg);
        
        if(isset($data['data'])){
            $json += $data;
        }else{
            $json['data'] = $data;
        }
        
        return response()->json($json);
    }
}