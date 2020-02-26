@extends('Layouts.main')
@section('css')
<link rel="stylesheet" href="/libs/bootstrap-table/dist/bootstrap-table.min.css" />
<link rel="stylesheet" href="/assets/css/app.min.css" />
<script src="/assets/laydate/laydate.js"></script>
<script src="/libs/layui/layui.all.js"></script>
<script src="/assets/js/jquery.min.js"></script>
<script src="/libs/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script src="/libs/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="/libs/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
<script src="/libs/bootstrap-table/dist/local/zh-cn.js"></script>
<script src="/scripts/plugins/tableExport.min.js"></script>
<script src="/assets/js/japp.js"></script>
@stop
@section('content')

{!! Html::showWithErrors($errors) !!}

<div class="box">
    <div id="list-toolbar">
        <button class="btn info theme-accent js-clear-vum-edit-form" data-toggle="modal" data-target="#modal-edit-window">
            <i class="fa fa-plus"></i> 添加资产
        </button>
        
    </div>
    
    <table id="list-table" class="table" data-url="/assets/list">
        <thead>
            <tr>
                <th data-field="id">资产ID</th>
                <th data-field="assets_name">资产名称</th>
                <th data-field="net_type">主网类型</th>
                <th data-field="contract_address">合约地址</th>
                <th data-field="decimals">小数位数</th>
                <th data-field="recharge_status" data-formatter="status">是否可充值、提现</th>
                <th data-field="created_at">添加时间</th>
                <th data-field="updated_at">修改时间</th>
                <th data-field="id" data-formatter="operate">操作</th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="modal-edit-window">
    <div class="modal-dialog modal-right w-50 w-auto-sm white dk b-l p-4">
        <form class="form-horizontal h-100 scrollable" id="submit_form" method="post" action="/assets/save">
            
            <input type="hidden" name="id" v-model="id" />
            
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">主网类型</label>
                <div class="col-lg-8">
                    <select name="net_type" class="form-control">
                        <option value="qki">qki</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">小数位数</label>
                <div class="col-lg-8">
                    <input type="text" name="decimals" v-model="decimals" class="form-control" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">合约地址</label>
                <div class="col-lg-8">
                    <input type="text" name="contract_address" v-model="contract_address" class="form-control" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">资产名称</label>
                <div class="col-lg-8">
                    <input type="text" name="assets_name" v-model="assets_name" class="form-control" />
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">是否可充值、提现</label>
                <div class="col-lg-8">
                    <label class="md-check">
                        <input type="radio" name="recharge_status" v-model="recharge_status" value="2" />
                        <i class="blue"></i>不能
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="md-check">
                        <input type="radio" name="recharge_status" v-model="recharge_status" value="1" />
                        <i class="blue"></i>可以
                    </label>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-lg-8 offset-lg-2">
                    <button type="submit" class="btn primary btn-sm p-x-md">
                        确定
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>$('head').find('script').eq(-1).text().match(/check-trash-js-load-method/) && $(window).off('pjax:success').on('pjax:success', function(){window.location.reload();});</script>

@stop
@section('js')

<script>
    var vars = {
        url_del: '{{url("assets/del")}}',
        post_ex_params: {
            _token: '{{csrf_token()}}',
            __no_check_repeat: 1
        }
    };
</script>

<script>
    
    (function($){
        var editForm = new Vue({
                el: '#modal-edit-window',
                data: {
                    id: 0,
                    decimals: '0',
                    contract_address: '',
                    assets_name: '',
                    recharge_status: 1
                }
            });
        
        
        $(document).on('click', '.js-clear-vum-edit-form', function(){
            editForm.id = 0;
            editForm.decimals = '0';
            editForm.contract_address = '';
            editForm.assets_name = '';
            editForm.recharge_status = 1;
        });
        
        
        init({
            list_tables: {
                table: '#list-table',
                toolbar: '#list-toolbar',
                search_form: '#list-search-form',
                format: {
                    operate: function(v, row, html, ec){
                        
                        ec.on('.js-btn-edit', function(id, row){
                            editForm.id = row.id;
                            editForm.decimals = row.decimals;
                            editForm.contract_address = row.contract_address;
                            editForm.assets_name = row.assets_name;
                            editForm.recharge_status = row.recharge_status;
                        });
                        
                        ec.on('.js-btn-del', function(id){
                            fn.confirm('确定要删除吗？', function(){
                                fn.ajaxPost(vars.url_del, {id: id}, vars.post_ex_params);
                            });
                        });
                        
                        return html.btn_edit + html.btn_del;
                    }
                }
            },
            submit_forms: {
                form: '#submit_form',
                ex_params: vars.post_ex_params
            }
        });
        
    })(jQuery);
</script>

@stop