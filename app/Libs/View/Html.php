<?php
/**
 * 生成后台控件
 * Date: 2016/10/11 0011
 * Time: 13:49
 */

namespace App\Libs\View;

use App\Model\Manage\ManagerList;
use Illuminate\Support\Str;

class Html
{
    /**
     * 文本框
     * @param string $name
     * @param string $value
     * @param array $attr
     * @param string $style
     * @return string
     */
    public static function text(string $name, string $value = null, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        $string = '<input type="text" name="%s" value="%s" class="form-control mr-2 %s" style="%s" %s />';
        return sprintf($string, $name, $value, $class, $style, implode(' ', $attrs));
    }

    /**
     * 密码框
     * @param string $name
     * @param array $attr
     * @param string $style
     * @return string
     */
    public static function password(string $name, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        $string = '<input type="password" name="%s" class="form-control %s" style="%s" %s />';
        return sprintf($string, $name, $class, $style, implode(' ', $attrs));
    }

    public static function group(string $input, string $title, $width = 12, string $desc = null): string
    {
        if ($desc) {
            $desc = '<ul class=" parsley-errors-list filled"><li style="color: #666">' . $desc . '</li></ul>';
        }
        $title = str_replace('*', '<span class="text-danger">*</span>', $title);
        $html = '<div class="col-md-' . $width . '">
                    <div class="form-group">
                        <label>' . $title . '</label>
	                        ' . $input . '
	                        ' . $desc . '
	                </div>
	            </div>';
        return $html;
    }

    /**
     * 下拉菜单
     * @param string $name
     * @param array $options
     * @param string|null $value
     * @param array $attr
     * @param string|null $class
     * @param string|null $style
     * @return string
     */
    public static function select(string $name, array $options, string $value = null, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        $option_str = null;
        foreach ($options as $k => $v) {
            if ($value == $k) {
                $option_str .= "<option value=\"{$k}\" selected>{$v}</option> \r\n";
            } else {
                $option_str .= "<option value=\"{$k}\">{$v}</option> \r\n";
            }
        }
        $string = '<select name="%s" class="form-control mr-2 %s" style="%s" %s >
                   %s
                   </select>';
        return sprintf($string, $name, $class, $style, implode(' ', $attrs), $option_str);
    }

    /**
     * 带搜索的select
     * @param string $name
     * @param array $options 选项,分组时必须是二维数组
     * @param array|null $value 选中值，多选时为数组
     * @param bool $multiple 是否多选
     * @param bool $group 选项是否分组
     * @param array $attr 属性
     * @param string|null $class css样式
     * @param string|null $style 样式
     * @return string
     */
    public static function select2(string $name, array $options, array $value = [], bool $multiple = false, bool $group = false, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        if ($multiple) {
            $attrs[] = 'multiple="multiple"';
        }
        $option_str = null;
        if ($group) {
            foreach ($options as $k => $v) {
                $option_str .= "<optgroup label=\"{$v['title']}\"> \r\n";
                foreach ($v['children'] as $kk => $vv) {
                    if (in_array($kk, $value)) {
                        $option_str .= "<option value=\"{$kk}\" selected>{$vv}</option> \r\n";
                    } else {
                        $option_str .= "<option value=\"{$kk}\">{$vv}</option> \r\n";
                    }
                }
                $option_str .= "</optgroup> \r\n";
            }
        } else {
            foreach ($options as $k => $v) {
                if (in_array($k, $value)) {
                    $option_str .= "<option value=\"{$k}\" selected>{$v}</option> \r\n";
                } else {
                    $option_str .= "<option value=\"{$k}\">{$v}</option> \r\n";
                }
            }
        }

        $string = '<select name="%s" class="form-control select2 %s" style="%s" %s >
                   %s
                   </select>';
        $string = sprintf($string, $name, $class, $style, implode(' ', $attrs), $option_str);
        return $string;
    }

    /**
     * 下拉菜单(多选)
     * @param string $name
     * @param array $options
     * @param array $attr
     * @param string|null $class
     * @param string|null $style
     * @return string
     */
    public static function multipleSelect(string $name, array $options, array $values = null, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        $option_str = null;
        $values = is_array($values) ? $values : [];
        foreach ($options as $k => $v) {
            if (in_array($k, $values)) {
                $option_str .= "<option value=\"{$k}\" selected>{$v}</option> \r\n";
            } else {
                $option_str .= "<option value=\"{$k}\">{$v}</option> \r\n";
            }
        }
        $string = '<select multiple="multiple" name="%s" class="form-control %s" style="%s" %s >
                   %s
                   </select>';
        return sprintf($string, $name, $class, $style, implode(' ', $attrs), $option_str);
    }

    /**
     * 文本域
     * @param string $name
     * @param string|null $value
     * @param array $attr
     * @param string|null $class
     * @param string|null $style
     * @return string
     */
    public static function textArea(string $name, string $value = null, array $attr = [], string $class = null, string $style = null): string
    {
        $attrs = [];
        foreach ($attr as $k => $v) {
            $attrs[] = $k . '="' . $v . '"';
        }
        $string = '<textarea type="text" name="%s" class="form-control %s" style="%s" %s />%s</textarea>';
        return sprintf($string, $name, $class, $style, implode(' ', $attrs), $value);
    }

    /**
     * 缩略图上传
     * @param string $input
     * @param string $name
     * @param string $uploaded
     * @return string
     */
    public static function thumb(string $input = 'thumb', string $name = '缩略图', string $uploaded = null)
    {
        $domain = config('file.domain');
        $url = $uploaded ? $domain . $uploaded : '';
        return '<div class="col-md-12" data-plugin="webuploader"><div class="form-group">
                    <label>' . $name . '</label>
                    <script>
                        var ' . $input . '_config = {url: "/common/img_upload",maxFiles: 1,paramName: "files",addRemoveLinks: true,acceptedFiles: \'image/*\',
                            autoDiscover: true,
                            init: function () {
                                var myDropzone = this;
                                var url = "' . $url . '";
                                if (url) {
                                    var mockFile = {name: \'当前图片\', size: 102400};
                                    myDropzone.emit("addedfile", mockFile);
                                    myDropzone.emit("thumbnail", mockFile, url);
                                    myDropzone.emit("complete", mockFile);
                                    myDropzone.options.maxFiles = myDropzone.options.maxFiles - 1;
                                }
                                myDropzone.on(\'removedfile\', function (file) {
                                    myDropzone.options.maxFiles = myDropzone.options.maxFiles + 1;
                                });
                                myDropzone.on(\'complete\', function (file) {
                                    myDropzone.options.maxFiles = myDropzone.options.maxFiles - 1;
                                });
                                myDropzone.on("maxfilesexceeded", function (file) {
                                    this.removeFile(file);
                                });
                            },
                            success: function (file,serverResponse) {
                                var url = serverResponse.data.url;
                                this.emit(\'thumbnail\', file, "' . $domain . '" + url);
                                this.createThumbnailFromUrl(file, "' . $domain . '" + url);
                                $(\'input[name="' . $input . '"]\').val(url);
                            }
                        }
                    </script>
                    <div class="dropzone white b-a b-3x b-dashed b-primary p-a rounded p-5 text-center" data-plugin="dropzone" data-option="' . $input . '_config">
                        <div class="dz-message"><h4 class="my-4">点击上传图片或将图片拖放到这里</h4>
                            <input value="' . $uploaded . '" type="hidden" name="' . $input . '">
                        </div>
                    </div>
                </div>
            </div>';
    }


    public static function gallery(string $input = 'images', string $name = '多图片', string $uploaded = null, int $limit = 100)
    {
        $domain = config('file.domain');
        return '<div class="col-md-12" data-plugin="webuploader">
                <div class="form-group">
                    <label>' . $name . '</label>
                    <script>
                        var domain = "' . $domain . '";
                        var ' . $input . '_config = {
                            url: "/common/img_upload",
                            maxFiles: ' . $limit . ',
                            paramName: "files",
                            addRemoveLinks: true,
                            acceptedFiles: \'image/*\',
                            autoDiscover: true,
                            getUrls: function (obj) {
                                var images = $(obj.element).find(\'.dz-image\').find(\'img\');
                                var value = [];
                                images.each(function () {
                                    value.push($(this).attr(\'src\').replace(domain, \'\'));
                                });
                                var input = $(\'input[name="' . $input . '"]\');
                                input.val(value.join(\',\'));
                            },
                            init: function () {
                                var myDropzone = this;
                                var images = "' . $uploaded . '";
                                if (images) {
                                    var mockFile;
                                    var url;
                                    images = images.split(\',\');
                                    for (var i in images) {
                                        url = domain + images[i];
                                        mockFile = {name: images[i], size: 102400};
                                        myDropzone.emit("addedfile", mockFile);
                                        myDropzone.emit("thumbnail", mockFile, url);
                                        myDropzone.emit("complete", mockFile);
                                        myDropzone.options.maxFiles = myDropzone.options.maxFiles - 1;
                                    }
                                }
                                myDropzone.on(\'removedfile\', function (file) {
                                    myDropzone.options.maxFiles = myDropzone.options.maxFiles + 1;
                                    this.options.getUrls(myDropzone);
                                });
                                myDropzone.on(\'complete\', function (file) {
                                    myDropzone.options.maxFiles = myDropzone.options.maxFiles - 1;
                                });
                                myDropzone.on("maxfilesexceeded", function (file) {
                                    this.removeFile(file);
                                });
                            },
                            success: function (file, serverResponse) {
                                var url = domain + serverResponse.data.url;
                                this.emit(\'thumbnail\', file, url);
                                this.createThumbnailFromUrl(file, url);
                                this.options.getUrls(this);
                            }
                        }
                    </script>
                    <div class="dropzone white b-a b-3x b-dashed b-primary p-a rounded p-5 text-center" data-plugin="dropzone" data-option="' . $input . '_config">
                        <div class="dz-message"><h4 class="my-4">点击上传图片或将图片拖放到这里</h4>
                            <input value="' . $uploaded . '" type="hidden" name="' . $input . '">
                        </div>
                    </div>
                </div>
            </div>';
    }

    public static function datePicker($name, $value, $options = [])
    {
        $options = $options ? $options : "{autoclose:true,format:'yyyy-mm-dd',language:'zh-cn',todayHighlight: true}";
        return '<input type="text" name="' . $name . '" value="' . $value . '" class="form-control" data-plugin="datepicker" data-option="' . $options . '">';
    }

    public static function datePickerRange($name1, $value1, $name2, $value2)
    {
        $html = <<<EOT
            <div class="input-group input-daterange mb-0" data-plugin="datepicker" data-option="{todayHighlight:true,format:'yyyy-mm-dd'}">
                <input type="text" class="form-control datetimepicker-input" name="{$name1}" value="{$value1}">
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control datetimepicker-input" name="{$name2}" value="{$value2}">
            </div>
EOT;
        
        return $html;
    }
    
    
    /**
     * 时间范围选择
     * @param string $nameStart 开始时间Name
     * @param string $nameEnd   结束时间Name
     */
    public static function timeRange($nameStart = null, $nameEnd = null){
        $nameStart = is_string($nameStart) && strlen($nameStart) === 1 ? $nameStart : 's';
        $nameEnd   = is_string($nameEnd)   && strlen($nameEnd)   === 1 ? $nameEnd   : 'e';
        
        $id    = uniqid('js-vue-time-range-id-');
        $stime = request()->input($nameStart);
        $etime = request()->input($nameEnd);
        
        return <<< EOF
            <div id="$id">
                <el-date-picker
                    v-model="date_vals"
                    name="$nameStart$nameEnd"
                    type="datetimerange"
                    start-placeholder="开始时间"
                    end-placeholder="结束时间">
                </el-date-picker>
            </div>
            <script>
                $(function(){
                    new Vue({
                        el: '#$id',
                        data: {
                            date_vals: ['$stime', '$etime']
                        },
                        watch: {
                            date_vals: function(date){
                                this.start = date ? date[0] : '';
                                this.end = date ? date[1] : '';
                            }
                        }
                    });
                });
            </script>
EOF;
    }
    
    
    /**
     * 更严谨的下拉框
     * @param string $name      Name
     * @param array  $options   选项
     * @param string $otherAttr 其它属性
     */
    public static function selectex(string $name, array $options, $otherAttr = ''){
        
        $selectedVal = request()->input($name, '');
        
        $optionStr = '';
        
        foreach($options as $val => $txt){
            $selected = '';
            
            if((string) $val === $selectedVal){
                $selected = 'selected="selected"';
            }
            
            $optionStr .= "<option value='" . htmlspecialchars($val) . "' $selected>" . htmlspecialchars($txt) . "</option>";
        }
        
        return "<select name='$name' class='form-control mr-2' $otherAttr>$optionStr</select>";
    }
    
    
    /**
     * 显示withErrors方法抛出的消息（消息前面加#将被显示为成功消息）
     * @param object $errors 模版上的错误变量
     */
    public static function showWithErrors($errors){
        $errorHtml = '';
        
        if(is_object($errors)){
            foreach($errors->all() as $error){
                $errTxt = preg_replace('/^#/', '', $error);
                
                if($errTxt === $error){
                    $alert = 'danger';
                    $icon  = 'times';
                }else{
                    $alert = 'success';
                    $icon  = 'check';
                }
                
                $errTxt = htmlspecialchars($errTxt);
                
                $errorHtml .= <<< EOF
                    <div class="alert alert-$alert">
                        <button type="button" class="close btn-md" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                        <i class="fa fa-$icon-circle"></i>
                        $errTxt
                    </div>
EOF;
            }
        }
        
        return $errorHtml;
    }
}