var no_repeat = document.getElementsByName("__no_repeat").length>0?document.getElementsByName("__no_repeat")[0].value:''

function refreshToken() {
    //刷新防重复提交token
    var obj = $('input[name="__no_repeat"]');
    if (obj.val()) {
        $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
            obj.val(data);
        });
    }
}

function success(that, msg) {
    that.$message({
        message: msg,
        type: 'success'
    });
}

function warning(that, msg) {
    that.$message({
        message: msg,
        type: 'warning'
    });
}

function error(that, msg) {
    that.$message.error(msg);
}