@extends('Layouts.edit')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <form method="post" action="/manage/node/edit_submit" class="edit-form form-horizontal">
                            <input type="hidden" name="id" value="{{$data->id}}"/>
                            {!! csrf_field() !!}
                            {!! repeat_field() !!}
                            {!! Html::group(Html::text('parent',$data->parent->name??'顶级',['disabled'=>'disabled']),'上级节点') !!}
                            {!! Html::group(Html::text('name',$data->name,['disabled'=>'disabled']),'节点名称') !!}
                            {!! Html::group(Html::text('uri',$data->uri,['disabled'=>'disabled']),'路由') !!}
                            {!! Html::group(Html::text('level',$data->level,['disabled'=>'disabled']),'层级') !!}
                            {!! Html::group(Html::text('method',$data->method,['disabled'=>'disabled']),'请求方式') !!}
                            {!! Html::group(Html::text('weight',$data->weight),'排序权重') !!}
                            @if($data->level==1)
                                {!! Html::group(Html::text('style',$data->style),'菜单图标') !!}
                            @endif

                            {!! Html::group(Html::switchery('hide',2,$data->hide),'在菜单中隐藏') !!}
                            <div class="form-group m-b-0">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
@stop