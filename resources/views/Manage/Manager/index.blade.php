@extends('Layouts.main')
@section('content')
    <div class="box">
        <div id="toolbar">
            <button class="btn info theme-accent" onclick="window.location='/manage/manager/edit'">
                <i class="fa fa-plus"></i> 新建
            </button>
            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">
                <label class="mr-1" for="username">用户名</label>
                {!! Html::text('username',\Request::input('username')) !!}
                <label class="mr-1" for="cn_name">姓名</label>
                {!! Html::text('cn_name',\Request::input('cn_name')) !!}
                <label class="mr-1" for="mobile">手机</label>
                {!! Html::text('mobile',\Request::input('mobile')) !!}
                <label class="mr-1" for="status">状态</label>
                {!! Html::select('status',\App\Model\Manage\ManagerList::$status,\Request::input('status')) !!}
                <button type="submit" class="btn info theme-accent ml-2">
                    搜索
                </button>
            </form>
        </div>
        {{--写在这里才不出BUG--}}
        <script>
            var action = '{{$base_path}}';
            window.callback = function () {
                $("#table").bootstrapTable('refresh');
            };
            operateFormatter = function (value, row) {
                var html = '<a class="text-accent mr-3" href="' + action + '/edit/' + row.id + '"><i class="fa fa-edit"></i>编辑</a>';
                html += '<a class="text-accent mr-3" href="' + action + '/power/' + row.id + '"><i class="badge badge-xs badge-o md"></i> 权限设置</a>';
                if (parseInt(row.id) !== 1) {
                    if (parseInt(row.status) === 1) {
                        html += '<a class="confirm mr-3 text-warning" data-callback="callback" href="javascript:;" data-url="' + action + '/disable/' + row.id + '"><i class="fa fa-ban"></i>禁用</a>';
                    } else {
                        html += '<a class="confirm mr-3 text-success" data-callback="callback" href="javascript:;" data-url="' + action + '/enable/' + row.id + '"><i class="fa fa-trash"></i>启用</a>';
                    }
                    html += '<a class="confirm text-danger mr-3" data-callback="callback" href="javascript:;" data-url="' + action + '/delete/' + row.id + '"><i class="fa fa-trash"></i>删除</a>';
                }
                return html;
            }
        </script>
        <table data-plugin="bootstrapTable" id="table" class="table" data-url="/manage/manager/page">
            <thead>
            <tr>
                <th data-checkbox="true"></th>
                <th data-field="id" data-sortable="true" data-formatter="textFormatter">#</th>
                <th data-field="username" data-sortable="true" data-formatter="textFormatter">用户名</th>
                <th data-field="cn_name" data-sortable="true" data-formatter="textFormatter">姓名</th>
                <th data-field="last_time" data-sortable="true" data-formatter="textFormatter">上次登录时间</th>
                <th data-field="last_ip" data-sortable="true" data-formatter="textFormatter">上次登录ip</th>
                <th data-field="status_text" data-formatter="textFormatter">状态</th>
                <th data-formatter="operateFormatter">操作</th>
            </tr>
            </thead>
        </table>
    </div>
@stop
@section('js')

@stop