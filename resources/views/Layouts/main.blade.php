<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$page_name}}-{{$site_name}}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="/assets/images/logo.svg">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" sizes="196x196" href="/assets/images/logo.svg">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/app.min.css">
    <link rel="stylesheet" href="/libs/element/index.css">
    <link rel="stylesheet" href="/libs/toastr/toastr.min.css">
    <script src="/assets/js/jquery.min.js"></script>
    @yield('css')
    <style>
        .jumpto input {
            height: 31px;
            width: 50px;
            margin-left: 5px;
            margin-right: 5px;
            text-align: center;
            display: inline-block;
        }
        .active>.nav-sub {
            max-height: 100rem;
        }
    </style>
</head>
<body>
<div class="app" id="app">
    <div id="aside" class="app-aside fade nav-expand dark" aria-hidden="true">
        <div class="sidenav modal-dialog dk">
            <div class="navbar lt dark" ui-class="dark"><a href="/" class="navbar-brand">
                    <svg viewBox="0 0 24 24" height="28" width="28" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M19.51 3.08L3.08 19.51c.09.34.27.65.51.9.25.24.56.42.9.51L20.93 4.49c-.19-.69-.73-1.23-1.42-1.41zM11.88 3L3 11.88v2.83L14.71 3h-2.83zM5 3c-1.1 0-2 .9-2 2v2l4-4H5zm14 18c.55 0 1.05-.22 1.41-.59.37-.36.59-.86.59-1.41v-2l-4 4h2zm-9.71 0h2.83L21 12.12V9.29L9.29 21z" fill="#fff" class="fill-theme"/>
                    </svg>
                    <img src="/assets/images/logo.png" alt="." class="hide">
                    <span class="hidden-folded d-inline">{{config('app.name')}}</span></a></div>
            <div class="flex hide-scroll">
                <div class="scroll">
                    <div class="nav-active-theme" data-nav>
                        <ul class="nav bg">
                            @foreach($system_menus as $v)
                                <li>
                                    <a @if(empty($v['children'])) href="{{$v['uri']=='/'?'/':'/'.$v['uri']}}" @endif>
                                        @if(!empty($v['children']))
                                            <span class="nav-caret"><i class="fa fa-caret-down"></i> </span>
                                        @endif
                                        <span class="nav-icon">
                                            <i class="{{$v['style']}}"></i>
                                        </span>
                                        <span class="nav-text">{{$v['name']}}</span>
                                    </a>
                                    @if(!empty($v['children']))
                                        <ul class="nav-sub">
                                            @foreach($v['children'] as $v2)
                                                <li>
                                                    <a href="/{{$v2['uri']}}"><span class="nav-text">{{$v2['name']}}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content" class="app-content box-shadow-0" role="main">
        <div class="content-header white box-shadow-0" id="content-header">
            <div class="navbar navbar-expand-lg"><a class="d-lg-none mx-2" data-toggle="modal" data-target="#aside">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                        <path d="M80 304h352v16H80zM80 248h352v16H80zM80 192h352v16H80z"/>
                    </svg>
                </a>
                <div class="navbar-text nav-title flex" id="pageTitle">
                    {{$page_name}}
                </div>
                <ul class="nav flex-row order-lg-2">
                    <li class="dropdown d-flex align-items-center">
                        <a href="javascript:;" data-toggle="dropdown" class="d-flex align-items-center"><span class="avatar w-32"><img src="/assets/images/a2.jpg" alt="..."></span></a>
                        <div class="dropdown-menu dropdown-menu-right w pt-0 mt-2 animate fadeIn">
                            <a class="dropdown-item" href="/manage/manager/profile"><span>修改密码</span></a>
                            <a class="dropdown-item" href="/login/logout">注销</a>
                        </div>
                    </li>
                    <li class="d-lg-none d-flex align-items-center">
                        <a href="#" class="mx-2" data-toggle="collapse" data-target="#navbarToggler">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512">
                                <path d="M64 144h384v32H64zM64 240h384v32H64zM64 336h384v32H64z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
                <strong class="nav flex-row order-lg-2 ml-1">{{\Auth::user()->cn_name}}</strong>
            </div>
        </div>
        <div class="content-main" id="content-main">
            <div class="padding">
                @yield('content')
            </div>
        </div>
        <div class="content-footer white" id="content-footer">
            <div class="d-flex p-3"><span class="text-sm text-muted flex">2018 &copy; {{config('app.name')}}.</span>
                <div class="text-sm text-muted">Version 1.0</div>
            </div>
        </div>
    </div>
</div>
<div id="setting">
    <div class="setting dark-white rounded-bottom" id="theme">
        <a href="#" data-toggle-class="active" data-target="#theme" class="dark-white toggle"><i class="fa fa-gear text-primary fa-spin"></i></a>
        <div class="box-header">
            <strong>UI设置</strong></div>
        <div class="box-divider"></div>
        <div class="box-body"><p id="settingLayout">
                <label class="md-check my-1 d-block"><input type="checkbox" name="fixedAside"> <i></i>
                    <span>菜单固定</span></label><label class="md-check my-1 d-block"><input type="checkbox" name="fixedContent">
                    <i></i>
                    <span>内容固定</span></label><label class="md-check my-1 d-block"><input type="checkbox" name="folded">
                    <i></i>
                    <span>收起菜单</span></label><label class="md-check my-1 d-block"><input type="checkbox" name="container">
                    <i></i>
                    <span>小视图</span></label><label class="md-check my-1 d-block"><input type="checkbox" name="ajax">
                    <i></i>
                    <span>Ajax加载页面</span></label><label class="pointer my-1 d-block" data-toggle="fullscreen" data-plugin="screenfull" data-target="fullscreen"><span class="ml-1 mr-2 auto"><i class="fa fa-expand d-inline"></i> <i class="fa fa-compress d-none"></i> </span><span>全屏模式</span></label>
            </p>
            <p>色调:</p>
            <p>
                <label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="primary">
                    <i class="primary"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="accent">
                    <i class="accent"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="warn">
                    <i class="warn"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="info">
                    <i class="info"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="success">
                    <i class="success"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="warning">
                    <i class="warning"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="theme" value="danger">
                    <i class="danger"></i></label></p>
            <div class="row no-gutters">
                <div class="col"><p>Logo</p>
                    <p>
                        <label class="radio radio-inline m-0 mr-1 ui-check"><input type="radio" name="brand" value="dark-white">
                            <i class="light"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="brand" value="dark">
                            <i class="dark"></i></label></p></div>
                <div class="col mx-2"><p>导航栏</p>
                    <p>
                        <label class="radio radio-inline m-0 mr-1 ui-check"><input type="radio" name="aside" value="white">
                            <i class="light"></i></label><label class="radio radio-inline m-0 mr-1 ui-check ui-check-color"><input type="radio" name="aside" value="dark">
                            <i class="dark"></i></label></p></div>
                <div class="col"><p>内容</p>
                    <div class="clearfix">
                        <label class="radio radio-inline ui-check"><input type="radio" name="bg" value="">
                            <i class="light"></i></label><label class="radio radio-inline ui-check ui-check-color"><input type="radio" name="bg" value="dark">
                            <i class="dark"></i></label></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-plugin="jqueryConfirm"></div>
<script src="/scripts/app.min.js"></script>
<script src="/scripts/vue.min.js"></script>
<script src="/libs/element/index.js"></script>
<script src="/scripts/common.js"></script>
<script src="/scripts/echarts/echarts.min.js"></script>
<script src="/libs/toastr/toastr.min.js"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    toastr.options.progressBar = true;
    toastr.options.positionClass = "toast-bottom-right";
    @if($errors->any())
    toastr.error('{{$errors->first()}}');
    @endif
    @if ($message = Session::get('success'))
    toastr.success('{{$message}}');
    @endif


</script>
@yield('js')
</body>
</html>