@extends('Layouts.main')
@section('content')
    <div class="box">
        <div id="toolbar">
            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">
                <label class="mr-1" for="username">管理员ID</label>
                {!! Html::text('admin_id',\Request::input('admin_id')) !!}
                <label class="mr-1" for="username">被操作人ID</label>
                {!! Html::text('uid',\Request::input('uid')) !!}
                <label class="mr-1" for="username">类型</label>
                {!! Html::select('type',\App\Service\OperationLogService::$types,\Request::input('type')) !!}
                <button type="submit" class="btn info theme-accent ml-2">
                    搜索
                </button>
            </form>
        </div>

        <table data-plugin="bootstrapTable" id="table" class="table" data-url="/manage/get-log-data">
            <thead>
            <tr>
                <th data-checkbox="true"></th>
                <th data-field="uid" data-sortable="true" data-formatter="textFormatter">被操作人ID</th>
                <th data-field="username" data-sortable="true" data-formatter="textFormatter">操作人</th>
                <th data-field="ip" data-sortable="true" data-formatter="textFormatter">ip</th>
                <th data-field="type_name" data-sortable="true" data-formatter="textFormatter">类型</th>
                <th data-field="reason" data-sortable="true" data-formatter="textFormatter">原因</th>
                <th data-field="before" data-sortable="true" data-formatter="textFormatter">操作前</th>
                <th data-field="after" data-sortable="true" data-formatter="textFormatter">操作后</th>
                <th data-field="created_at" data-sortable="true" data-formatter="textFormatter">时间</th>
            </tr>
            </thead>
        </table>
    </div>
@stop
@section('js')

@stop