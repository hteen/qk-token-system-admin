var confirm_config = {
    icon: 'fa fa-lightbulb-o',
    theme: 'modern',
    closeIcon: true,
    animation: 'scaleX',
    type: 'orange',
    closeAnimation: 'scaleX',
    animateFromElement: false,
    title: '高能预警',
    content: '请确定是否执行本操作！',
    buttons: {
        ok: {
            text: '确定',
            btnClass: 'btn-warning',
            keys: ['enter', 'shift'],
        },
        cancel: {
            text: '还是算了吧',
            btnClass: 'btn-default',
            keys: ['esc'],
        }
    }
};
(function () {
    $(document).on('click', '.confirm', function () {
        var url = $(this).attr('data-url');
        confirm_config.buttons.ok.action = function () {
            $.ajax({
                type: "GET",// 请求方式
                url: url,// 请求url地址
                dataType: "json",// 数据返回类型
                success: function (data) {
                    if (parseInt(data.code) !== 200) {
                        $.alert(data.message);
                    } else {
                        window.callback();
                    }
                },
                timeout: 3000,// 超时设置,如果3秒内请求无响应,则执行error定义的方法
                error: function (data) {
                    $.alert('系统错误');
                },
                async: true,// 默认设置为true，所有请求均为异步请求。
            });
        };
        $.confirm(confirm_config);
    });
})(jQuery);