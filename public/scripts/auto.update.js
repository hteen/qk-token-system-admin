/**
 * 自动更新列表中的字段
 */
(function ($) {
    var defaults = {
        url: '',//地址
        //初始化
        init: function (obj) {
            var self = this;
            //绑定事件
            obj.change('submit', function () {
                return self.update($(this));
            });
        },
        //提交
        update: function (obj) {
            var self = this;
            var field = obj.attr('name');
            if (obj.attr('data-switchery')) {
                var value = obj.prop('checked') ? 2 : 1;
            } else {
                var value = obj.val();
            }

            var id = obj.attr('data-id');
            $.ajax({
                type: "get",// 请求方式
                url: self.url + '/' + id + '/' + field + '/' + encodeURIComponent(value),// 请求url地址
                dataType: "json",// 数据返回类型
                success: function (data) {
                    if (data.data.reload) {
                        window.location.reload();
                    }
                },
                timeout: 3000,// 超时设置,如果3秒内请求无响应,则执行error定义的方法
                error: function (data) {
                    self.error(data);
                },
                async: true,// 默认设置为true，所有请求均为异步请求。
            });
        },
        //出现错误
        error: function (result) {
            if (result.status == 200) {
                return;
            }
            var response = result.responseText;
            try {
                data = $.parseJSON(response);
            } catch (err) {
                layer.msg('系统错误(错误代码:' + result.status + ')，请联系开发者');
                return;
            }
            this.notifyError(data.message);
        },
        //单条提示信息
        notifyError: function (message) {
            layer.msg(message);
        }
    };

    $.fn.autoUpdate = function (options) {
        //是否正在提交中
        options = $.extend(defaults, options);
        options.init(this);
    };
})(jQuery);
