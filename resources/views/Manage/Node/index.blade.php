@extends('Layouts.main')
@section('content')
    <script>
        function reload() {
            $.ajax({
                url: '/manage/node/load',
                data: {},
                success: function (data) {
                    window.location.reload();
                },
                error: function (data) {
                    $.alert(data.responseJSON);
                },
                dataType: 'JSON'
            });
        }
    </script>
    <div class="box">
        <div id="toolbar">
            <button class="btn info theme-accent" onclick="reload()">
                <i class="fa fa-plus"></i> 重新加载路由
            </button>
        </div>
        {{--写在这里才不出BUG--}}
        <script>
            var action = '{{$base_path}}';
            window.callback = function () {
                $("#table").bootstrapTable('refresh');
            };
            updateWeightFormatter = function (value, row) {
                return '<input type="text" name="weight" value="' + value + '" class="form-control ajax-update" data-url="' + action + '/update/' + row.id + '/weight/">';
            };
            updateStyleFormatter = function (value, row) {
                value = value ? value : '';
                return '<i style="float: left;" class="' + value + '"></i><input style="float: left;" type="text" name="style" value="' + value + '" class="form-control ajax-update" data-url="' + action + '/update/' + row.id + '/style/">';
            };
        </script>
        <table data-plugin="bootstrapTable" id="table" class="table" data-url="{{$base_path}}/page">
            <thead>
            <tr>
                <th data-checkbox="true"></th>
                <th data-field="id" data-sortable="true" data-formatter="textFormatter">#</th>
                <th data-field="name" data-sortable="true" data-formatter="textFormatter">名称</th>
                <th data-field="weight" data-sortable="true" data-formatter="updateWeightFormatter">排序</th>
                <th data-field="style" data-sortable="true" data-formatter="updateStyleFormatter">样式</th>
            </tr>
            </thead>
        </table>
    </div>
@stop