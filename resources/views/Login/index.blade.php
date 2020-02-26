<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$page_name}}-{{$site_name}}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="/assets/images/logo.svg">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" sizes="196x196" href="/assets/images/logo.svg">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/app.min.css">
</head>
<body>
<div class="d-flex flex-column flex">
    <div class="navbar light bg pos-rlt box-shadow">
        <div class="mx-auto"><a href="/" class="navbar-brand">
                <svg viewBox="0 0 24 24" height="28" width="28" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M19.51 3.08L3.08 19.51c.09.34.27.65.51.9.25.24.56.42.9.51L20.93 4.49c-.19-.69-.73-1.23-1.42-1.41zM11.88 3L3 11.88v2.83L14.71 3h-2.83zM5 3c-1.1 0-2 .9-2 2v2l4-4H5zm14 18c.55 0 1.05-.22 1.41-.59.37-.36.59-.86.59-1.41v-2l-4 4h2zm-9.71 0h2.83L21 12.12V9.29L9.29 21z" fill="#fff" class="fill-theme"/>
                </svg>
                <img src="/assets/images/logo.png" alt="." class="hide">
                <span class="hidden-folded d-inline">{{config('app.name')}}</span></a></div>
    </div>
    <div id="content-body">
        <div class="py-5 text-center w-100">
            <div class="mx-auto w-xxl w-auto-xs">
                <div class="px-3">
                    <form data-plugin="sha256" class="m-t-20" method="post" id="ajax_form" action="/login/login" redirect_url="/" name="form">
                        {{csrf_field()}}
                        {!! repeat_field() !!}
                        <div class="form-group">
                            <input class="form-control" name="username" type="text" required placeholder="用户名">
                        </div>

                        <div class="form-group">
                            <input class="form-control" name="password" type="password" required placeholder="密码">
                        </div>
                        
                        <div class="mb-3">
                            <label class="md-check"><input type="checkbox" name="remember" value="1"><i class="primary"></i> 保持登录状态</label>
                        </div>

                        <button type="submit" class="btn primary">登录</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-plugin="jqueryConfirm"></div>
<script src="/scripts/app.min.js"></script>
<script src="/scripts/ajax.form.js"></script>
<script>
    $(function () {
        $('#ajax_form').ajaxForm({
            setData: function () {
                self.data = {
                    username: $('input[name="username"]').val(),
                    opt: $('input[name="opt"]').val(),
                    password: $('input[name="password"]').val().length > 0 ? sha256($('input[name="password"]').val()) : '',
                    remember: $('input[name="remember"]').prop('checked') ? 1 : 0,
                    _token: $('input[name="_token"]').val()
                };
                return self.data;
            }
        });
    });
</script>
</body>
</html>

