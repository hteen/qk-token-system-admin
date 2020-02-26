@extends('Layouts.main')
@section('content')

    <div class="box">
        <div id="toolbar">

            <form id="search-form" class="form-inline m-2" role="form" action="" method="get">
                <label class="mr-1" for="id">UID</label>
                {!! Html::text('id',\Request::input('id')) !!}
                <label class="mr-1" for="username">用户名</label>
                {!! Html::text('username',\Request::input('username')) !!}
                <label class="mr-1" for="reg_ip">注册ip</label>
                {!! Html::text('reg_ip',\Request::input('reg_ip')) !!}
                <label class="mr-1" for="status">状态</label>
                {!! Html::select('status',\App\Model\Users::$status_label,\Request::input('status')) !!}
                <label class="mr-1" for="uid_invite">该UID邀请的人</label>
                {!! Html::text('uid_invite',\Request::input('uid_invite')) !!}
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
            
            function setDis(id, type=1) {
                $("#statusCheck").css("display", "flex");
                $("#sub").css("display", "block");
                $("#apply_id").val(id);
            }

            var is_click = true;

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

            function apply_verify(){
                if(!is_click)
                {
                    return false;
                }
                is_click = false;
                
                var apply_id = $("#apply_id").val(),
                    status = $('input[name="status"]:checked').val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    url: '/user/disable',
                    data: {
                        uid: apply_id,
                        status: status,
                        __no_repeat: $('input[name="__no_repeat"]').val()
                    },
                    dataType: 'JSON',
                    type: "post",// 请求方式
                    success: function (data) {

                        if(data.code == 200)
                        {
                            $("#table").bootstrapTable('refresh');
                            $('#mb-4-b').modal('hide');
                            $('#reason').val("");
                            $('#created_at_search').val("");

                        }else{
                            $.alert(data.message)
                        }
                        //刷新防重复提交token
                        var obj = $('input[name="__no_repeat"]');
                        if (obj.val()) {
                            $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                                obj.val(data);
                            });
                        }
                        is_click = true;
                    },
                });
            }

            textStatus = function(value,row){

                var html;
                if(row.status==1){
                    html = '<span style="color:green">正常</span>';
                }else{
                    html= '<span style="color:red">禁用</span>';
                }
                return html;
            }
            
            operateFormatter = function (value, row) {

                var html = '';
                
                html +='<a class="text-danger mr-3  text-warning" style="color:#ffc107!important" data-toggle="modal" data-target="#mb-4-b" onclick="setDis('+row.id+')" ><i class="fa fa-ban"></i>禁用管理</a>';
                
                return '<div style="width: 200px">'+html+'</div>';
            };

            var select_all = true;
            function checkboxSelectAll() {
                if (select_all) {
                    $("input[name=user_comment]").prop("checked", true);
                    select_all = false;
                } else {
                    $("input[name=user_comment]").prop("checked", false);
                    select_all = true;
                }
            };
            checkboxFormatter = function (value, row) {
                var html = '<label class="ui-check m-0"><input type="checkbox" name="user_comment" value="' + row.id + '"><i></i></label>';
                return html;
            }
        </script>
        
        
        <table data-show-jumpto="true" data-plugin="bootstrapTable" id="table" class="table" data-url="/user/page">
            <thead>
            <tr>
                <th data-formatter="checkboxFormatter"><label class="ui-check m-0">
                        <input type="checkbox" name="select_all"><i onclick="checkboxSelectAll();"></i></label>
                </th>
                <th data-field="id" data-sortable="true" data-formatter="textFormatter">UID</th>
                <th data-field="username" data-sortable="true" data-formatter="textFormatter">用户名</th>
                <th data-field="invite_code" data-sortable="true" data-formatter="textFormatter">邀请码</th>
                <th data-field="invite_uid" data-sortable="true" data-formatter="textFormatter">邀请人uid</th>
                <th data-field="reg_ip" data-sortable="true" data-formatter="textFormatter">注册IP</th>
                <th data-field="status_name" data-sortable="true" data-formatter="textStatus">状态</th>
                <th data-field="created_at" data-sortable="true" data-formatter="textFormatter">注册时间</th>
                <th data-field="updated_at" data-sortable="true" data-formatter="textFormatter">上次修改</th>
                <th data-formatter="operateFormatter">操作</th>
            </tr>
            </thead>
        </table>

        <div id="mb-4-b" class="modal black-overlay" data-backdrop="false"  data-plugin="task" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">禁用管理</h5>
                    </div>
                    <div class="modal-body text-center p-lg">
                        <div class="form-group row" id="statusCheck">
                            <label class="col-sm-6 col-form-label" for="verify_status">状态:</label>
                            <div class="col-sm-6">
                                启用：<input type="radio" value="1" name="status">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;禁用:<input type="radio" value="2" name="status">
                            </div>
                        </div>
                        <?php echo repeat_field(); ?>
                        <input type="hidden" value="" id="apply_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark-white p-x-md" data-dismiss="modal">取消</button>
                        <button type="button" id="sub" class="btn success p-x-md" onclick="apply_verify()">提交</button>
                    </div>
                </div>
            </div>
        </div>

       
    </div>
@stop
@section('js')
@stop