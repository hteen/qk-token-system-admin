@extends('Layouts.main')
@section('content')
    <div class="box">
        <div class="col-lg-6 p-4">
            <div class="form-group">
                <label class="col-sm-2 col-form-label">用户名</label>
                <div class="col-sm-10">
                    <strong>@{{ user.username }}</strong>
                </div>
            </div>
            {!! repeat_field() !!}
            <el-input
                    placeholder="输入关键字进行过滤"
                    v-model="filterText">
            </el-input>

            <el-tree
                    class="filter-tree"
                    :data="data"
                    show-checkbox
                    :props="defaultProps"
                    :filter-node-method="filterNode"
                    :default-checked-keys="checked"
                    node-key="id"
                    accordion
                    ref="tree">
            </el-tree>
            <el-button type="primary" @click="submit">保存</el-button>

        </div>
    </div>
@stop
@section('js')
    <script src="/scripts/ajax.form.js"></script>
    <script>
        $(function () {
            $('#ajax-form').ajaxForm();
        });
        new Vue({
            el: '#app',
            data: {
                filterText: '',
                id: '{{$id}}',
                data: null,
                checked: [],
                defaultProps: {
                    children: 'children',
                    label: 'name'
                },
                user: '',
            },
            watch: {
                filterText: function(val) {
                    this.$refs.tree.filter(val);
                }
            },

            methods: {
                filterNode: function(value, data) {
                    if (!value) return true;
                    return data.name.indexOf(value) !== -1;
                },
                submit: function() {
                    var that = this
                    var power = this.$refs.tree.getCheckedNodes();
                    var arr = [];
                    for(var i=0;i<power.length;i++){
                        arr.push(power[i]['uri'])
                    }
                    half = this.$refs.tree.getHalfCheckedNodes();
                    for(var i=0;i<half.length;i++){
                        arr.push(half[i]['uri'])
                    }
                    $.post('{{$base_path}}/power_submit', {
                        _token: '{!! csrf_token() !!}',
                        power: arr,
                        __no_repeat: document.getElementsByName("__no_repeat")[0].value,
                        id: this.id
                    }, function (rs) {
                        that.refreshToken();
                        if (rs.code==200) {
                            that.$message({
                                message: '保存成功',
                                type: 'success'
                            });
                        }
                    })
                },
                getData: function() {
                    var that = this
                    $.get('{{$base_path}}/get-power/'+this.id, {
                        _token: '{!! csrf_token() !!}',
                        __no_repeat: document.getElementsByName("__no_repeat")[0].value,
                    }, function (rs) {
                        that.data = rs.data.tree
                        that.user = rs.data.data
                        that.checked = rs.data.checked
                    })
                },
                refreshToken: function() {
                    //刷新防重复提交token
                    var obj = $('input[name="__no_repeat"]');
                    if (obj.val()) {
                        $.get('/common/refresh_repeat_token/' + obj.val(), function (data) {
                            obj.val(data);
                        });
                    }
                }
            },
            mounted() {
                this.getData()
            }
        })
    </script>
@stop