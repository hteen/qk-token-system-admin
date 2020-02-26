@extends('Layouts.main')
@section('content')
    <div class="box">
        <div id="toolbar">
            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">

                <label class="mr-1" for="uid"></label>
                {!! Html::text('uid',\Request::input('uid'), ['placeholder' => '用户ID']) !!}
                <label class="mr-1" for="from"></label>
                {!! Html::text('from',\Request::input('from'), ['placeholder' => '转出地址']) !!}
                <label class="mr-1" for="to"></label>
                {!! Html::text('to',\Request::input('to'), ['placeholder' => '转入地址']) !!}
                <label class="mr-1" for="hash"></label>
                {!! Html::text('hash',\Request::input('hash'), ['placeholder' => '转账hash']) !!}
                <label class="mr-1" for="block_hash"></label>
                {!! Html::text('block_hash',\Request::input('block_hash'), ['placeholder' => '区块hash']) !!}
                <label class="mr-1" for="block_hash"></label>
                {!! Html::text('payee',\Request::input('payee'), ['placeholder' => '接收地址']) !!}
                <label class="mr-1" for="block_number"></label>
                {!! Html::text('block_number',\Request::input('block_number'), ['placeholder' => '区块高度']) !!}
                <label class="mr-1" for="assets_type"></label>
                {!! Html::text('assets_type',\Request::input('assets_type'), ['placeholder' => '资产类型']) !!}
                <label class="mr-1" for="status"></label>
                {!! Html::select('status', $statusLabel, \Request::input('status')) !!}
                <label class="mr-1" for="tx_status"></label>
                {!! Html::select('tx_status', [
                    '0' => '交易状态',
                    '1' => '成功',
                    '2' => '失败'
                ], \Request::input('tx_status')) !!}
                
                <button type="submit" class="btn info theme-accent ml-2">
                    搜索
                </button>

            </form>
        </div>
        {{--写在这里才不出BUG--}}
        <script>
            window.callback = function () {
                $("#table").bootstrapTable('refresh');
            };
            fromFormatter = function (value, row){
                var html = '';
                html += '<a target="_blank" href="https://qkiscan.cn/address/'+ row.from +'" style="color: dodgerblue"><strong>' + '点击查看' + '</strong></a>';
                return html;
            };
            toFormatter = function (value, row){
                var html = '';
                html += '<a target="_blank" href="https://qkiscan.cn/address/'+ row.to +'" style="color: dodgerblue"><strong>' + '点击查看' + '</strong></a>';
                return html;
            };
            hashFormatter = function (value, row) {
                var html = '';
                html += '<a target="_blank" href="https://qkiscan.cn/tx/'+ row.hash +'" style="color: dodgerblue"><strong>' + '点击查看' + '</strong></a>';
                return html;
            };
            blockHashFormatter = function (value, row) {
                var html = '';
                html += '<a target="_blank" href="https://qkiscan.cn/block/detail?hash='+ row.block_hash +'" style="color: dodgerblue"><strong>' + '点击查看' + '</strong></a>';
                return html;
            };
            statusFormatter = function (value, row) {
                var html = '';
                if (row.status == 2) {
                    html += '<strong style="color: green;">已处理</strong>';
                } else {
                    html += '<strong style="color: red;">未处理</strong>';
                }
                return html;
            };
            uidFormatter = function (value, row) {
                var html = '';
                if (row.user) {
                    html += '<strong>'+ row.user.username +'<br/>(ID: '+ row.user.id +')</strong>';
                }
                return html;
            };
            txStatusFormatter = function (value, row) {
                var html = '';
                if (row.tx_status == 1) {
                    html += '<strong style="color: green;">成功</strong>';
                } else {
                    html += '<strong style="color: red;">失败</strong>';
                }
                return html;
            };
            operateFormatter = function (value, row) {
                var html;
                if(row.status == 1)
                {
                    html = '<a class="text-accent mr-3 confirm" href="javascript:;" data-url="/transactions/manual-recharge/' + row.id + '" ><i class="fa fa-edit"></i>手动分配</a>';
                    html += '<a class="text-success mr-3 confirm" href="javascript:;" data-url="/transactions/mark-done/' + row.id + '" ><i class="fa fa-check"></i>标记为已处理</a>';
                }
                
                return html;
            };
        </script>
        <table data-plugin="bootstrapTable" id="table" class="table" data-url="/transactions/page">
            <thead>
            <tr>
                <th data-field="id" data-sortable="true" data-formatter="textFormatter">#</th>
                <th data-field="uid" data-sortable="false" data-formatter="uidFormatter">用户</th>
                <th data-field="from" data-sortable="false" data-formatter="fromFormatter">转出地址</th>
                <th data-field="to" data-sortable="false" data-formatter="toFormatter">转入地址</th>
                <th data-field="hash" data-sortable="false" data-formatter="hashFormatter">转账hash</th>
                <th data-field="block_hash" data-sortable="false" data-formatter="blockHashFormatter">区块hash</th>
                <th data-field="block_number" data-sortable="false" data-formatter="textFormatter">区块高度</th>
                <th data-field="gas_price" data-sortable="false" data-formatter="textFormatter">矿工费</th>
                <th data-field="amount" data-sortable="false" data-formatter="textFormatter">数量</th>
                <th data-field="status" data-sortable="false" data-formatter="statusFormatter">状态</th>
                <th data-field="assets_type" data-sortable="false" data-formatter="textFormatter">资产类型</th>
                <th data-field="tx_status" data-sortable="false" data-formatter="txStatusFormatter">交易状态</th>
                <th data-field="token_id" data-sortable="false" data-formatter="textFormatter">通证类型id</th>
                <th data-field="data_id" data-sortable="false" data-formatter="textFormatter">处理对应的数据id</th>
                <th data-field="remark" data-sortable="false" data-formatter="textFormatter">备注</th>
                <th data-field="payee" data-sortable="false" data-formatter="textFormatter">接收地址</th>
                <th data-field="token_tx_amount" data-sortable="true" data-formatter="textFormatter">通证交易数量</th>
                <th data-field="updated_at" data-sortable="false" data-formatter="textFormatter">时间</th>
                <th data-valign="middle" data-formatter="operateFormatter">操作</th>
            </tr>
            </thead>
        </table>
    </div>


<script>$('head').find('script').eq(-1).text().match(/check-trash-js-load-method/) && $(window).off('pjax:success').on('pjax:success', function(){window.location.reload();});</script>

@stop
@section('js')
    <script src="/libs/layui/layui.all.js"></script>
    <script src="/assets/js/japp.js"></script>

    <script type="text/javascript">
        
        function refreshToken()
        {
            //刷新防重复提交token
            var obj = $('input[name="__no_repeat"]');
            if (obj.val()) {
                $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                    obj.val(data);
                });
            }
        }
        
        
        init({
            list_tables: [],
            submit_forms: {
                form: '#submit_form',
                ex_params: {
                    _token: '{{csrf_token()}}',
                    __no_check_repeat: 1
                }
            }
        });
    </script>
@stop
@section('js')
@stop