
/**
 * 单页通用JS
 * @param $
 * @param win
 */
(function($, win){
    'use strict';
    
    var fn = {
            
            /**
             * 标准列表页初始化操作
             * @param config 配置对象
             */
            listPageInit: function self(config){
                var defConfig = {
                    list_tables: [{
                        table: '#list-table',
                        toolbar: '#list-toolbar',
                        search_form: '#list-search-form'
                    }],
                    submit_forms: [{
                        form: '#submit_form',
                        ex_params: {__no_check_repeat: 1},
                        not_ajax_elem: '.js-not-ajax'
                    }]
                };
                
                var sta = self._static || (self._static = {}),
                    listTables = fn.isobj(config, 'list_tables') ? config.list_tables : defConfig.list_tables,
                    submitForms = fn.isobj(config, 'submit_forms') ? config.submit_forms : defConfig.submit_forms;
                
                listTables = $.isArray(listTables) ? listTables : [listTables];
                submitForms = $.isArray(submitForms) ? submitForms : [submitForms];
                
                if(sta.inited){
                    return ;
                }
                sta.inited = true;
                
                $(function(){
                    $.each(listTables, function(i, table){
                        new bTable(table);
                    });

                    $.each(submitForms, function(i, form){
                        new sForm(form);
                    });
                });
            },
            
            /**
             * 图片上传，会返回清空重置图片显示方法
             * @param conf 配置，一次仅支持一个
             */
            imageUpload: function(conf){
                var imgUp = new imageUpload(conf);
                
                return function(imgs){
                    imgUp.resetImages(imgs);
                };
            },
            
            /**
             * POST的AJAX请求
             * @param url      请求地址
             * @param post     请求数据
             * @param backfn   回调
             * @param exparams 扩展参数
             */
            ajax: function(url, post, backfn, exparams){
                if(!$.isFunction(backfn)){
                    backfn = function(){};
                }
                
                if($.isArray(post) || fn.isobj(post)){
                    post = $.param(post);
                }else{
                    post = $(post).serialize();
                }
                
                if(fn.isobj(exparams)){
                    post += '&' + $.param(exparams);
                }
                
                fn.loading(true);
                
                $.post(url, post, function(res){
                    if(!fn.isobj(res, 'code', 'msg')){
                        res = {code: 1, msg: '服务器错误'};
                    }
                    
                    fn.loading();
                    backfn(res);
                    
                }, 'json');
            },
            
            /**
             * AJAX提交，并判断返回结果，成功刷新页面，失败显示错误信息
             * @param url      请求地址
             * @param post     请求数据
             * @param exparams 扩展参数
             */
            ajaxPost: function(url, post, exparams){
                fn.ajax(url, post, function(res){
                    if(parseInt(res.code) === 0){
                        fn.alert(res.msg, function(){
                            win.location.reload();
                        });
                    }else{
                        fn.error(res.msg);
                    }
                }, exparams);
            },
            
            /**
             * 加载效果 
             * @param isOpen 是开始加载还是结束加载
             */
            loading: function self(isOpen){
                var sta = self._static || (self._static = {});
                
                if(isOpen){
                    sta.loading = win.layer.load(0, {time: 60 * 1000, shade: 0.1});
                }else{
                    win.layer.close(sta.loading);
                }
            },
            
            /**
             * 普通消息提示
             * @param msg    消息文本
             * @param backfn 消息显示结束回调
             */
            alert: function(msg, backfn){
                win.layer.msg(fn.htmlspecialchars(msg), {icon: 6, time: 2000, anim: 1, shade: 0.1, shadeClose: true}, function(){
                    if($.isFunction(backfn)){
                        backfn();
                    }
                });
            },
            
            /**
             * 错误消息提示
             * @param msg 消息文本
             */
            error: function(msg){
                win.layer.msg(fn.htmlspecialchars(msg), {icon: 5, time: 5000, anim: 6, shade: 0.1, shadeClose: true});
            },
            
            /**
             * 确认框
             * @param msg    消息框
             * @param backfn 确认回调
             */
            confirm: function(msg, backfn){
                win.layer.confirm(fn.htmlspecialchars(msg), {
                    title: '确认',
                    btn: ['确定', '取消']
                    
                }, function(index){
                    win.layer.close(index);
                    
                    if($.isFunction(backfn)){
                        backfn();
                    }
                });
            },
            
            /**
             * 将字符串中特殊字符转换为HTML实体
             * @param str 待转换的字符串
             */
            htmlspecialchars: function(str){
                if($.type(str) !== 'string'){
                    return '';
                }
                return str.split('&').join('&amp;').split('"').join('&quot;').split('<').join('&lt;').split('>').join('&gt;');
            },
            
            /**
             * 补零
             * @param num 待补零字符串
             * @param bit 补零后总位数
             */
            addZero: function(num, bit){
                num = num + '';
                bit = bit > 0 ? bit : 2;
                
                if(bit > num.length){
                    return Array(bit - num.length + 1).join(0) + num;
                }
                return num;
            },
            
            /**
             * 秒级时间戳转时间字符串
             * @param timestamp
             */
            toTime: function(timestamp){
                var date;
                
                if(!timestamp || !fn.isnum(timestamp, 0)){
                    return false;
                }
                
                date = new Date(timestamp * 1000);
                
                return date.getFullYear() + '-' + fn.addZero(date.getMonth() + 1) + '-' + fn.addZero(date.getDate()) +
                       ' ' + fn.addZero(date.getHours()) + ':' + fn.addZero(date.getMinutes()) + ':' + fn.addZero(date.getSeconds());
            },
            
            /**
             * 判断值是否是普通对象，第一个参数是待判断值，如果第二个参数传入true，则仅判断是否是对象，后面可以传入需要检查是否存在的键名
             */
            isobj: function(){
                var args = $.merge([], arguments),
                    obj = args.shift(),
                    res = true;

                if((args[0] === true && args.shift() && $.type(obj) === 'object') || $.isPlainObject(obj)){
                    $.each(args, function(i, key){
                        return res = fn.isstdin(key) && (key in obj);
                    });
                    return res;
                }
                return false;
            },
            
            /**
             * 判断值是否是字符串
             * @param val 待判断值
             */
            isstr: function(val){
                return $.type(val) === 'string';
            },

            /**
             * 判断值是否是数字
             * @param val     待判断值
             * @param decimal 判断小数位数，默认不判断
             */
            isnum: function(val, decimal){
                var dec;

                if($.type(val) === 'number' && isFinite(val)){
                    if($.type(decimal) === 'number'){
                        dec = val.toString().match(/\.(\d+)$/);

                        return decimal === (dec ? dec[1].length : 0);
                    }
                    return true;
                }
                return false;
            },

            /**
             * 判断值是否是标准输入值，即只能是数字和字符串
             * @param val 待判断值
             */
            isstdin: function(val){
                return fn.isstr(val) || fn.isnum(val);
            },
            
            /**
             * jQuery的param方法升级版，转换前会对格式进行判断，并且可以同时传递多组参数，也可以直接传递字符串，最后会连接为一个参数字符串
             */
            urlParams: function(){
                var params = [];

                $.each(arguments, function(i, param){
                    if(fn.isstr(param) || (($.isArray(param) || fn.isobj(param) || param instanceof $) && (param = $.param(param)))){
                        params.push(param);
                    }
                });
                return params.join('&');
            },
            
            /**
             * 获取唯一值字符串
             */
            uniqid: function(){
                return (Math.random() + '_' + Math.random()).replace(/0\./g, '');
            },
            
            /**
             * 生成随机整数
             * @param min 可能生成的最小数
             * @param max 可能生成的最大数
             */
            rand: function(min, max){
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        };
    
    
    /**
     * 图片上传
     * @param config 配置
     */
    function imageUpload(config){
        var defImgSizeWidth = 0,
            defImgSizeHeight = 200,
            
            fileSelector = fn.isobj(config, 'file_selector') ? config.file_selector : '',
            emptyName = fn.isobj(config, 'empty_name') ? config.empty_name : 'empty_image',
            showConr = fn.isobj(config, 'show_conr') ? config.show_conr : null,
            multiple = fn.isobj(config, 'multiple') ? config.multiple : false,
            imgSizeW = fn.isobj(config, 'img_size_width') ? config.img_size_width : defImgSizeWidth,
            imgSizeH = fn.isobj(config, 'img_size_height') ? config.img_size_height : defImgSizeHeight,
            
            me = this,
            fileHtml = '',
            $initFile = $(fileSelector),
            initFileName = $initFile.attr('name'),
            
            emptyFlag = function(isEmpty){
                var $file = $(fileSelector),
                    $hidden = $('<input type="hidden" />').attr('name', emptyName).val(1);
                
                $file.siblings('[name="' + emptyName + '"]').remove();
                
                if(isEmpty){
                    $file.after($hidden);
                }
            },
            
            showImages = function(files){
                var $lay = clearImagesShow();
                
                files = files || [];
                
                emptyFlag(!files.length);
                
                $.each(files, function(i, file){
                    var $img = $('<img>').attr('src', $.type(file) === 'string' ? file : win.URL.createObjectURL(file)),
                        $item = $('<div class="js-img-upload-item">').css({
                            float: 'left',
                            margin: '5px',
                            border: '1px #E6E6E6 solid'
                        });
                    
                    imgSizeW && $img.css('width', imgSizeW);
                    imgSizeH && $img.css('height', imgSizeH);
                    
                    $lay.before($item.html($img));
                });
            },
            
            clearImagesShow = function(){
                var $conr = $(showConr),
                    $frame = $('<div>').css('position', 'relative'),
                    $lay = $('<div>').css({
                        position: 'absolute',
                        width: '100%',
                        height: '0%',
                        top: '0px',
                        left: '0px',
                        background: 'rgba(0,0,0,.6)',
                        overflow: 'hidden',
                        cursor: 'pointer'
                        
                    }).html($('<a href="javascript:;"><i class="fa fa-trash-o"></i> 删除</a>').css({
                        position: 'absolute',
                        width: '100%',
                        top: '50%',
                        left: '0px',
                        color: '#FFF',
                        'margin-top': '-10px',
                        'text-align': 'center'
                    }));
                
                $lay.one('click', function(){
                    me.resetImages();
                });
                
                $frame.append($lay).append($('<div>').css('clear', 'both')).on('mouseenter', function(){
                    if($(this).children('.js-img-upload-item').length){
                        $lay.stop().animate({height: '100%'}, 300);
                    }
                }).on('mouseleave', function(){
                    $lay.stop().animate({height: '0%'}, 300);
                });
                
                $conr.html($frame);
                return $lay;
            };
        
        me.resetImages = function(imgs){
            $(fileSelector).replaceWith(fileHtml);
            showImages($.type(imgs) === 'string' ? [imgs] : imgs);
        };
        
        if(multiple){
            $initFile.attr('multiple', 'multiple');
            
            if(initFileName && initFileName.match(/[^\]]$/)){
                $initFile.attr('name', initFileName + '[]');
            }
        }else{
            $initFile.removeAttr('multiple');
        }
        
        fileHtml = $initFile.prop('outerHTML');
        
        $(win.document).on('change', fileSelector, function(){
            showImages($(this).prop('files'));
        });
    }
    
    
    /**
     * AJAX表单提交
     * @param formConf 配置
     */
    function sForm(formConf){
        var defExParams = {__no_check_repeat: 1},
            
            form = fn.isobj(formConf, 'form') ? formConf.form : formConf,
            exparams = fn.isobj(formConf, 'ex_params') ? formConf.ex_params : defExParams;
        
        $(form).on('submit', function(){
            var isNotAjax = false,
                $exElem = $(),
                $form = $(this),
                action = $form.attr('action');
            
            $form.find('[type="file"]').each(function(){
                if($(this).prop('files').length){
                    isNotAjax = true;
                    return false;
                }
            });
            
            if(isNotAjax){
                $.each(exparams, function(name, value){
                    $exElem = $exElem.add($('<input type="hidden" />').attr('name', name).val(value));
                });
                
                $form.attr('enctype', 'multipart/form-data').append($exElem);
                return true;
            }
            
            fn.ajaxPost(action, $form, exparams);
            return false;
        });
    }
    
    
    /**
     * bootstrapTable再封装
     * @param tableConf 配置
     */
    function bTable(tableConf){
        var defToolBar = '#list-toolbar',
            defSearchForm = '#list-search-form',
            defFormat = {
                text: function(value){
                    return value === void(0) || value === null ? '' : $('<strong>').text(value);
                },
                time: function(value){
                    return $('<strong>').text(fn.toTime(value) || '-');
                },
                image: function(value){
                    return $('<img>').attr('src', value).css('height', 30);
                },
                status: function(value, row, html){
                    switch(parseInt(value)){
                        case 1:
                            return $(html.icon_yes).addClass('text-success');
                        default:
                            return $(html.icon_no).addClass('text-warning');
                    }
                },
                input: function(value, row, html, eventConr){
                    eventConr.on('change', '.js-input', function(v, r){
                        console.log(value, row, v, r, this);
                    });
                    return $(html.input).attr('value', value).css('width', '50%');
                },
                operate: function(value, row, html, eventConr){
                    eventConr.on('.js-btn-search', function(v, r){
                        console.log(value, row, v, r, this);
                    });
                    return html.btn_search;
                }
            },
            
            html = new function(){
                this.icon_del = '<i class="fa fa-trash-o"></i>';
                this.icon_edit = '<i class="fa fa-edit"></i>';
                this.icon_yes = '<i class="fa fa-check-circle"></i>';
                this.icon_no = '<i class="fa fa-times-circle"></i>';
                this.icon_search = '<i class="fa fa-search"></i>';
                this.icon_enable = '<i class="fa fa-check"></i>';
                this.icon_disable = '<i class="fa fa-times"></i>';
                this.btn = '<a class="js-btn mr-3" href="javascript:;"><span class="js-text">操作</span></a>';
                this.btn_edit = $(this.btn).addClass('js-btn-edit text-accent').prepend(this.icon_edit).find('.js-text').text('编辑').end().attr('data-toggle', 'modal').attr('data-target', '#modal-edit-window').prop('outerHTML');
                this.btn_del = $(this.btn).addClass('js-btn-del text-warning').prepend(this.icon_del).find('.js-text').text('删除').end().prop('outerHTML');
                this.btn_show = $(this.btn).addClass('js-btn-show text-accent').prepend(this.icon_search).find('.js-text').text('查看').end().prop('outerHTML');
                this.btn_enable = $(this.btn).addClass('js-btn-enable text-success').prepend(this.icon_enable).find('.js-text').text('启用').end().prop('outerHTML');
                this.btn_disable = $(this.btn).addClass('js-btn-disable text-warning').prepend(this.icon_disable).find('.js-text').text('禁用').end().prop('outerHTML');
                this.input = '<input type="text" class="form-control js-input" />';
            }(),
            
            table = fn.isobj(tableConf, 'table') ? tableConf.table : tableConf,
            toolBar = fn.isobj(tableConf, 'toolbar') ? tableConf.toolbar : defToolBar,
            searchForm = fn.isobj(tableConf, 'search_form') ? tableConf.search_form : defSearchForm,
            formatter = fn.isobj(tableConf, 'format') ? $.extend({}, defFormat, tableConf.format) : defFormat,
            
            winformatFnNamePx = '_japp_bootstrap_table_formatter_handle_' + fn.uniqid() + '_',
            
            queryParams = function(params){
                var query = $(searchForm).serialize(),
                    param = {
                        limit: params.limit ? params.limit : 20,
                        offset: params.offset ? params.offset : 0,
                        order_by: params.sort ? params.sort : '',
                        order_way: params.order
                    };
                
                return fn.urlParams(query, param);
            },
            
            formatterHandle = function(value, row, type){
                var eventConr, selector, $html;
                
                if($.isFunction(formatter[type])){
                    selector = '_japp_bootstrap_table_element_selector_' + fn.uniqid();
                    eventConr = new eventConrBind(selector, value, row);
                    $html = $('<div>').html(formatter[type](value, row, html, eventConr));
                    
                    if(eventConr.hasBind){
                        $html.attr('id', selector);
                    }
                    return $html.prop('outerHTML');
                }
                
                return value + '';
            },
            
            $table = $(table),
            $heads = $table.find('thead').find('tr').eq(0).find('th');
        
        $heads.each(function(){
            var fhfnName,
                $head = $(this),
                type = $head.data('formatter');
            
            type = fn.isobj(formatter, type) ? type : 'text';
            
            fhfnName = winformatFnNamePx + type;
            
            win[fhfnName] = function(value, row){
                return formatterHandle(value, row, type);
            };
            
            $head.data('formatter', fhfnName);
        });
        
        changeBootstrapTable();
        
        $table.bootstrapTable({
            method: 'get',
            toolbar: toolBar,                   //指定工具栏
            striped: true,                      //是否显示行间隔色
            mobileResponsive: true,
            showExport: true,
            sortName: '',
            sortOrder: '',
            dataField: "data",
            iconsPrefix: "fa",
            pageNumber: 1,                      //初始化加载第一页，默认第一页
            pagination: true,                   //是否分页
            queryParamsType: 'limit',           //查询参数组织方式
            queryParams: queryParams,           //请求服务器时所传的参数
            sidePagination: 'server',           //指定服务器端分页
            slientSort: true,                   //指定服务器端分页
            pageSize: 20,                       //单页记录数
            pageList: [10, 20, 50, 100, 200],   //分页步进值
            showRefresh: true,                  //刷新按钮
            showColumns: true,
            clickToSelect: true,                //是否启用点击选中行
            paginationShowPageGo: true
        });
        
        
        function eventConrBind(selector, value, row){
            var me = this;
            
            me.hasBind = false;
            
            me.on = function(eventType, subSelector, fn){
                var fullSelector;
                
                if($.isFunction(subSelector)){
                    fn = subSelector;
                    subSelector = eventType;
                    eventType = 'click';
                }
                
                fullSelector = '#' + selector + ' ' + subSelector;
                
                $(table).off(eventType, fullSelector).on(eventType, fullSelector, function(){
                    return fn.call(this, value, row);
                });
                
                me.hasBind = true;
                return me;
            };
        }
        
        
        function changeBootstrapTable(){
            var sprintf = $.fn.bootstrapTable.utils.sprintf;

            if(fn.isobj($.fn.bootstrapTable, 'has-change')){
                return ;
            }
            $.fn.bootstrapTable['has-change'] = true;

            $.extend($.fn.bootstrapTable.defaults, {
                showJumpto: false,
                exportOptions: {}
            });

            $.extend($.fn.bootstrapTable.locales, {
                formatJumpto: function () {
                    return '跳转';
                }
            });
            
            $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales);

            var BootstrapTable = $.fn.bootstrapTable.Constructor,
                _initPagination = BootstrapTable.prototype.initPagination;

            BootstrapTable.prototype.initPagination = function () {
                _initPagination.apply(this, Array.prototype.slice.apply(arguments));

                if (this.options.showJumpto) {
                    var that = this,
                        $pageGroup = this.$pagination.find('ul.pagination'),
                        $jumpto = $pageGroup.find('li.jumpto');

                    if (!$jumpto.length) {
                        $jumpto = $([
                            '<li class="jumpto">',
                            '<input type="text" class="form-control">',
                            '<button class="btn' +
                            sprintf(' btn-%s', this.options.buttonsClass) +
                            sprintf(' btn-%s', this.options.iconSize) +
                            '" title="' + this.options.formatJumpto() + '" ' +
                            ' type="button">'+this.options.formatJumpto(),
                            '</button>',
                            '</li>'].join('')).appendTo($pageGroup);

                        $jumpto.find('button').click(function () {
                            that.selectPage(parseInt($jumpto.find('input').val()));
                        });
                    }
                }
            };
        }
    }
    
    
    win.fn = fn;
    win.init = fn.listPageInit;
    
})(jQuery, window);