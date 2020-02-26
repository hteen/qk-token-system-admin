@extends('Layouts.main')
@section('content')
    <div class="box">
        <div id="toolbar">
            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">
                <label class="mr-1" for="username">管理员ID</label>
                {!! Html::text('uid',\Request::input('uid')) !!}

                <button type="submit" class="btn info theme-accent ml-2">
                    搜索
                </button>
            </form>
        </div>

        <table data-plugin="bootstrapTable" id="table" class="table" data-url="/manage/query-data">
            <thead>
            <tr>
                <th data-checkbox="true"></th>
                <th data-field="uid" data-sortable="true" data-formatter="textFormatter">UID</th>
                <th data-field="username" data-sortable="true" data-formatter="textFormatter">用户名</th>
                <th data-field="ip" data-sortable="true" data-formatter="textFormatter">ip</th>
                <th data-field="path" data-sortable="true" data-formatter="textFormatter">请求路径</th>
                <th data-field="method" data-sortable="true" data-formatter="textFormatter">请求方式</th>
                <th data-field="input" data-sortable="true" data-formatter="textFormatter">请求参数</th>
                <th data-field="created_at" data-sortable="true" data-formatter="textFormatter">时间</th>
            </tr>
            </thead>
        </table>
    </div>
@stop
@section('js')

@stop