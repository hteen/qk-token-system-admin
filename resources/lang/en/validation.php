<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | following language lines contain default error messages used by
    | validator class. Some of these rules have multiple versions such
    | as size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 必须是可接受的值',
    'active_url' => ':attribute 不是合法的URL地址',
    'after' => ':attribute 必须是 :date 后的日期',
    'alpha' => ':attribute 只能是字母',
    'alpha_dash' => ':attribute 只能是字母和下划线',
    'alpha_num' => ':attribute 只能是字母和数字',
    'array' => ':attribute 必须是数组',
    'before' => ':attribute 必须是 :date 以前的日期',
    'between' => [
        'numeric' => ':attribute 必须是 :min ~ :max 之间的数字',
        'file' => ':attribute 大小必须在 :min ~ :max 之间',
        'string' => ':attribute 长度必须在 :min ~ :max 之间',
        'array' => ':attribute 必须包含 :min ~ :max 个值',
    ],
    'boolean' => ':attribute 的值必须是 true 或 false',
    'confirmed' => '两次输入的:attribute不一致',
    'date' => ':attribute 不是合法的日期',
    'date_format' => ':attribute 格式不正确 :format',
    'different' => ':attribute 和 :other 的值必须不同',
    'digits' => ':attribute 必须是 :digits 数字',
    'digits_between' => ':attribute 大小必须在 :min ~ :max 之间',
    'dimensions' => ':attribute 不是合法的图片',
    'distinct' => ':attribute 有重复的值',
    'email' => ':attribute 不是正确的邮箱格式',
    'exists' => ':attribute 选中值不存在',
    'file' => ':attribute 必须是文件',
    'filled' => ':attribute 不能为空',
    'image' => ':attribute 必须是图片',
    'in' => ':attribute 值不正确',
    'in_array' => ':attribute 不是 :other 下的项目',
    'integer' => ':attribute 必须是整数',
    'ip' => ':attribute 不是正确的Ip地址',
    'json' => ':attribute 不是正确的JSON字符串',
    'max' => [
        'numeric' => ':attribute 不能大于 :max.',
        'file' => ':attribute 大小不能超过 :max KB',
        'string' => ':attribute 长度不能超过 :max 个字符',
        'array' => ':attribute 包含的值不能大于 :max 个',
    ],
    'mimes' => ':attribute 必须是mime类型为 :values 的文件',
    'min' => [
        'numeric' => ':attribute 不能小于 :min',
        'file' => ':attribute 大小不能小于 :min KB',
        'string' => ':attribute 至少 :min 个字符',
        'array' => ':attribute 至少包含 :min 个元素',
    ],
    'not_in' => ':attribute 不在可接受值的范围内',
    'numeric' => ':attribute 必须是数字',
    'present' => ':attribute 必须出现在输入数据中',
    'regex' => ':attribute 格式不正确',
    'required' => ':attribute 不能为空',
    'required_if' => '在 :other 等于 :value 时，:attribute 不能为空',
    'required_unless' => '当 :other 是 :values 中的某个值时，:attribute 不能为空',
    'required_with' => ' 当 :values 出现在 present 中时，:attribute 不能为空',
    'required_with_all' => '当 :values 出现在 present 中时，:attribute 不能为空',
    'required_without' => '当 :values 未出现在 present 中时，:attribute 不能为空',
    'required_without_all' => '当 :values 未出现在 present 中时，:attribute 不能为空',
    'same' => ':attribute 必须和 :other 的值匹配',
    'size' => [
        'numeric' => ':attribute 必须是 :size',
        'file' => ':attribute 大小必须是 :size KB',
        'string' => ':attribute 长度必须是 :size 个字符',
        'array' => ':attribute 必须包含 :size 个元素',
    ],
    'string' => ':attribute 必须是字符串',
    'timezone' => ':attribute 必须是合法的时区',
    'unique' => ':attribute 相同的记录已存在',
    'url' => ':attribute 格式不正确',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'username' => '用户名',
        'password' => '密码',
        'mobile' => '手机号',
        'name' => '名称',
        'cn_name' => '姓名',
        'group' => '用户组',
        'code' => '代号',
        'title' => '标题',
        'seo_title' => 'seo标题',
        'seo_keywords' => 'seo关键词',
        'seo_description' => 'seo描述',
        'parent_id' => '父级ID',
        'content' => '详细内容',
        'address' => '地址',
        'number' => '数量',
        'total_money' => '合计金额',
        'thumb' => '缩略图',
        'cate_id' => '分类',
        'original_url' => '原始URL',
        'preview_url' => '预览URL',
        'color_id' => '色调',
        'size' => '压缩包尺寸',
        'page_number' => '页面数',
        'download_times' => '下载次数',
        'preview_times' => '预览次数',
        'hits' => '点击(浏览)次数',
        'like_number' => '点赞数',
        'favorite_number' => '收藏数',
        'lang_id' => '语言',
        'compatibility_ids' => '兼容性',
        'business_ids' => '适用行业',
        'score' => '评分',
        'price' => '直接购买价格',
        'is_chosen' => '是否精选',
        'style_id' => '风格',
        'year' => '年份',
    ],

];
