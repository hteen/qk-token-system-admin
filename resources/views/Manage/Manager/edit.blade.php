@extends('Layouts.main')
@section('content')
    <div class="box">
        <div class="col-lg-6 p-4">
            <form method="post" action="{{$base_path}}/edit_submit" id="ajax-form" class="form-horizontal" redirect_url="{{$base_path}}">
                {!! csrf_field() !!}
                {!! repeat_field() !!}
                <input type="hidden" name="id" value="{{$data['id']}}">
                <input type="hidden" name="type" value="2">
                @if($data['id']>0)
                    {!! Html::group(Html::text('username',$data['username'],['disabled'=>'disabled']),'用户名') !!}
                @else
                    {!! Html::group(Html::text('username',$data['username']),'用户名') !!}
                @endif
                {!! Html::group(Html::password('password',['placeholder'=>'不修改密码请留空']),'密码') !!}
                {!! Html::group(Html::password('password_confirmation',['placeholder'=>'不修改密码请留空']),'确认密码') !!}
                {!! Html::group(Html::text('cn_name',$data['cn_name']),'姓名') !!}

                <div class="form-group m-b-0 col-sm-12">
                    <button type="submit" class="btn btn-info waves-effect waves-light">提交</button>
                </div>
            </form>
        </div>
    </div>
@stop
@section('js')
    <script src="/scripts/ajax.form.js"></script>
    <script>
        $(function () {
            $('#ajax-form').ajaxForm();
        });
    </script>
@stop