<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Common extends Base
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    /**
     * 图片上传
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AjaxExceptions
     * @throws \App\Exceptions\ValidateExceptions
     */
    public function imgUpload()
    {
        $config = config('file');
        $file = $this->request->file('files');
        if ($file->isExecutable()) {
            $this->ajaxError('请不要上传可疑文件');
        }
        $ext = $file->extension();
        if (!in_array(strtolower($ext), $config['img']['extensions'])) {
            $this->ajaxError('系统不允许上传此类文件');
        }
        if ($file->getSize() > $config['img']['extensions']) {
            $this->ajaxError('图片超出大小限制');
        }
        $path = date('Y/m/');
        $file_name = md5(microtime() . Str::random()) . '.' . $ext;
        $file->move($config['img']['base_path'] . $path, $file_name);
        return response()->json(['with_domain' => $config['domain'] . '/img/' . $path . $file_name, 'without_domain' => '/img/' . $path . $file_name]);
    }

    /**
     * 文件上传
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AjaxExceptions
     * @throws \App\Exceptions\ValidateExceptions
     */
    public function fileUpload()
    {
        $config = config('file');
        $file = $this->request->file('attachment');
        if ($file->isExecutable()) {
            $this->ajaxError('请不要上传可疑文件');
        }
        $ext = $file->extension();
        $mime = $file->getMimeType();
        if (!$ext) {
            if ($mime == 'application/vnd.ms-office') {
                $ext = 'xls';
            }
        }
        if ($ext == 'bin') {
            if ($mime == 'application/octet-stream') {
                $ext = 'xlsx';
            }
        }

        if (!in_array(strtolower($ext), $config['file']['extensions'])) {
            $this->ajaxError('系统不允许上传此类文件');
        }
        if ($file->getSize() > $config['file']['extensions']) {
            $this->ajaxError('文件超出大小限制');
        }
        $path = date('Y/m/');
        $file_name = md5(microtime() . Str::random()) . '.' . $ext;
        $file->move($config['file']['base_path'] . $path, $file_name);
        return response()->json(['with_domain' => $config['domain'] . '/file/' . $path . $file_name, 'without_domain' => '/file/' . $path . $file_name, 'name' => $file->getClientOriginalName()]);
    }

    /**
     * 刷新防重复提交token
     * @param string|null $old
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refreshRepeatToken(string $old = null)
    {
        return response(repeat_refresh($old));
    }
}