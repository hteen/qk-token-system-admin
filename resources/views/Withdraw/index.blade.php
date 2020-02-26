@extends('Layouts.main')
@section('content')

    <link rel="stylesheet" type="text/css" href="/libs/bootstrap-datepicker/dist/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/libs/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
    <style>
        .bootstrap-datetimepicker-widget{
            z-index:99999!important;

        }
    </style>
    <div class="box">

        <div id="toolbar">
            {{--<button class="btn info theme-accent" onclick="window.location='/user/add'">
                <i class="fa fa-plus"></i> 新建
            </button>--}}
            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">
                <label class="mr-1" for="username">ID</label>
                {!! Html::text('id',\Request::input('id')) !!}
                <label class="mr-1" for="username">UID</label>
                {!! Html::text('uid',\Request::input('uid')) !!}
                <label class="mr-1" for="username">地址</label>
                {!! Html::text('address',\Request::input('address')) !!}
                <label class="mr-1" for="username">交易HASH</label>
                {!! Html::text('tx_hash',\Request::input('tx_hash')) !!}
                <label class="mr-1" for="username">资产类型</label>
                {!! Html::select('assets_type',\App\Model\Assets::$type_label,\Request::input('assets_type')) !!}
                <label class="mr-1" for="username">状态</label>
                {!! Html::select('status',\App\Model\WithdrawLog::$status_label,\Request::input('status')) !!}
                <label class="mr-1" for="username">交易状态</label>
                {!! Html::select('tx_status',\App\Model\WithdrawLog::$txStatusLabel,\Request::input('tx_status')) !!}
                <label class="mr-1" for="start_time">创建时间</label>
                <div class="form-group input-daterange" data-plugin="datepicker" data-option="{}">
                    <input type="text" name="start_time" value="{{\Request::input('start_time')}}" class="form-control datetimepicker-input " id="datetimepicker1"  />
                    <span class="input-group-addon">-</span>
                    <input type="text" name="end_time" value="{{\Request::input('end_time')}}"  class="form-control datetimepicker-input " id="datetimepicker2"  />
                </div>
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
            nameFormatter = function (value, row) {
                var html = '';
                if(row.user)
                {
                    html = row.user.username + '(ID:'+ row.uid +')';
                }
                return html;
            };
            addressFormatter = function (value, row) {
                var html = '<a style="color: #53a6fa;" href="https://qkiscan.cn/address/'+row.address+'" target="_blank">' + row.address + '</a>';
                return html;
            };
            hashFormatter = function (value, row) {
                var html = '<a style="color: #53a6fa;" href="https://qkiscan.cn/tx/'+row.tx_hash+'" target="_blank">' + row.tx_hash + '</a>';
                return html;
            };
            operateFormatter = function (value, row) {
                var html = "";
                return html;
            };
        </script>
        <table data-plugin="bootstrapTable" id="table" class="table" data-url="/withdraw/withdraw-log-page">
            <thead>
            <tr>
                <th data-field="id" data-sortable="true" data-formatter="textFormatter">ID</th>
                <th data-sortable="true" data-formatter="nameFormatter">用户</th>
                <th data-sortable="true" data-formatter="addressFormatter">地址</th>
                <th data-field="assets_type" data-sortable="true" data-formatter="textFormatter">类型</th>
                <th data-field="amount" data-sortable="true" data-formatter="textFormatter">数量</th>
                <th data-field="fee" data-sortable="true" data-formatter="textFormatter">手续费</th>
                <th data-field="status_name" data-sortable="true" data-formatter="textFormatter">状态</th>
                <th data-field="tx_status_name" data-sortable="true" data-formatter="textFormatter">转账状态</th>
                <th data-sortable="true" data-formatter="hashFormatter">交易HASH</th>
                <th data-field="net_type" data-sortable="true" data-formatter="textFormatter">主网</th>
                <th data-field="ip" data-sortable="true" data-formatter="textFormatter">IP</th>
                <th data-field="ip_address" data-sortable="true" data-formatter="textFormatter">地区</th>
                <th data-field="user_agent" data-sortable="true" data-formatter="textFormatter">浏览器信息</th>
                <th data-field="updated_at" data-sortable="true" data-formatter="textFormatter">上次修改</th>
                <th data-field="created_at" data-sortable="true" data-formatter="textFormatter">创建时间</th>
                <th data-formatter="operateFormatter">操作</th>
            </tr>
            </thead>
        </table>
        <div id="mb-3-b" class="modal black-overlay" data-backdrop="false"  data-plugin="task" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">拒绝提现</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="remark">拒绝原因</label>
                            <div class="col-sm-10">
                                <textarea id="remark" class="form-control" rows="5" name="remark"></textarea>
                            </div>
                        </div>
                        <input type="hidden" value="" id="log_id">
                        {!! repeat_field() !!}
                        {!! csrf_field() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">取消</button>
                        <button type="button" class="btn success p-x-md" id="sub_btn">提交</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="change-address" class="modal black-overlay" data-backdrop="false"  data-plugin="task" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">修改地址</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="remark">地址</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="address" value="">
                            </div>
                        </div>
                        <input type="hidden" value="" id="change_log_id">
                        {!! repeat_field() !!}
                        {!! csrf_field() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">取消</button>
                        <button type="button" class="btn success p-x-md" id="change_btn">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
<script>$('head').find('script').eq(-1).text().match(/check-trash-js-load-method/) && $(window).off('pjax:success').on('pjax:success', function(){window.location.reload();});</script>

    
    <script src="/scripts/jquery.min.js"></script>
    <script src="/libs/bootstrap-datepicker/dist/js/bootstrap.min.js"></script>
    <script src="/libs/moment/min/moment-with-locales.min.js"></script>
    <script src="/libs/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="/libs/bootstrap-datepicker/dist/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $("#datetimepicker1").datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            todayBtn: true,
            language:'cn'
        });
        $("#datetimepicker2").datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            todayBtn: true,
            language:'cn'
        });
    </script>
@stop
@section('js')
<script type="text/javascript">
    function setId(id)
    {
        $("#log_id").val(id);
    }

    function setChangeAddressId(id)
    {
        $("#change_log_id").val(id);
    }

    var is_click = true;
    $("#sub_btn").click(function () {
        if(!is_click)
        {
            return false;
        }
        is_click = false;
        var id = $("#log_id").val(),
            remark = $('#remark').val(),
            no_repeat = document.getElementsByName("__no_repeat")[0].value;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: '/withdraw/refuse',
            data: {
                id: id,
                remark: remark,
                __no_repeat:no_repeat,
            },
            dataType: 'JSON',
            type: "POST",// 请求方式
            success: function (data) {
                $.alert(data.message);
                //刷新防重复提交token
                var obj = $('input[name="__no_repeat"]');
                if (obj.val()) {
                    $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                        obj.val(data);
                    });
                }
                if(data.code == 200)
                {
                    window.location.reload();
                }
                is_click = true;
            },
        });
    });

    $("#change_btn").click(function () {
        if(!is_click)
        {
            return false;
        }
        is_click = false;
        var id = $("#change_log_id").val(),
            address = $('#address').val(),
            no_repeat = document.getElementsByName("__no_repeat")[0].value;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: '/withdraw/change-address',
            data: {
                id: id,
                address: address,
                __no_repeat:no_repeat,
            },
            dataType: 'JSON',
            type: "POST",// 请求方式
            success: function (data) {
                $.alert(data.message);
                //刷新防重复提交token
                var obj = $('input[name="__no_repeat"]');
                if (obj.val()) {
                    $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                        obj.val(data);
                    });
                }
                if(data.code == 200)
                {
                    window.location.reload();
                }
                is_click = true;
            },
        });
    });
</script>
@stop