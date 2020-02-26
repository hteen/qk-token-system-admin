/**
 * ajax提交表单
 */
(function ($) {
    var defaults = {
        submitting: false,
        //初始化
        init: function (obj) {
            var self = this;
            //绑定事件
            obj.submit('submit', function (event) {
                event.preventDefault();
                return self.submit(obj);
            });
        },
        setData: function () {
        },
        //不允许提交
        disable: function (form) {
            this.submitting = true;
            form.find('button[type="submit"]').prop('disabled', true);
        },
        //允许提交
        enable: function (form) {
            this.submitting = false;
            form.find('button[type="submit"]').prop('disabled', false);
        },
        //提交
        submit: function (form) {
            var self = this;
            if (this.submitting === true) {
                //提交中
                return false;
            }
            self.disable(form);
            var url = form.attr('action');
            var data = self.setData();
            data = data ? data : form.serialize();
            $.ajax({
                type: "POST",// 请求方式
                url: url,// 请求url地址
                data: data,
                dataType: "json",// 数据返回类型
                success: function (data) {
                    if (parseInt(data.code) !== 200) {
                        $.alert(data.message);
                        self.refreshToken();
                    } else {
                        self.success(data, form);
                    }
                    window.setTimeout(function () {
                        self.enable(form);
                    }, 1000);
                },
                timeout: 300000,// 超时设置,如果3秒内请求无响应,则执行error定义的方法
                error: function (data) {
                    self.refreshToken();
                    var response = data.responseText;
                    if (parseInt(data.status) !== 422) {
                        $.alert('系统错误');
                    }else{
                        data = $.parseJSON(response);
                        self.formError(data.errors, form);
                    }
                    window.setTimeout(function () {
                        self.enable(form);
                    }, 1000);
                },
                async: true,// 默认设置为true，所有请求均为异步请求。
            });
        },
        //提交成功
        success: function (data, form) {
            var url = data.redirect_url ? data.redirect_url : form.attr('redirect_url');
            window.setTimeout(function () {
                if (url) {
                    window.location = url;
                } else {
                    window.location.reload();
                }
            }, 1000);
        },
        refreshToken: function () {
            //刷新防重复提交token
            var obj = $('input[name="__no_repeat"]');
            if (obj.val()) {
                $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                    obj.val(data);
                });
            }
        },
        //表单内提示错误信息
        formError: function (data, form) {
            var j = 1;
            //找不到的用弹窗提示
            var msg = '';
            for (var i in data) {
                var obj = form.find('input[name="' + i + '"],textarea[name="' + i + '"],select[name="' + i + '"]');
                msg += '<li>' + data[i] + '</li>';
                if (j == 1) {
                    obj.focus();
                }
                obj.removeClass('is-invalid').addClass('is-invalid');
                j++;
            }
            $.alert('<ol style="margin: 0;padding: 0;">' + msg + '</ol>');
            //值改变后清除错误信息
            form.on('change', 'input,textarea', function () {
                $(this).removeClass('is-invalid');
            });
        },
    };

    $.fn.ajaxForm = function (options) {
        //是否正在提交中
        options = $.extend(defaults, options);
        options.init(this);
    };
})(jQuery);
