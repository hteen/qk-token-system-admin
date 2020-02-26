/**
 * @author Jay <jwang@dizsoft.com>
 */

(function ($) {
    'use strict';
    var sprintf = $.fn.bootstrapTable.utils.sprintf;

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
})(jQuery);

function queryParams(params) {
    var data = $('#search-form').serialize();
    data += '&limit=' + (params.limit ? params.limit : 20);
    data += '&offset=' + (params.offset ? params.offset : 0);
    data += '&order_by=' + (params.sort ? params.sort : '');
    data += '&order_way=' + params.order;
    return data;
}

function textFormatter(data) {
    return data ? '<strong>' + data + '</strong>' : '-';
}

function getIdSelections($table) {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
        return row.id;
    });
}

(function ($) {
    "use strict";
    $(document).on('change', '.ajax-update', function () {
        var value = $(this).val(), url = $(this).attr('data-url');
        $.get(url + value, function (data) {
            if (parseInt(data.code) === 200) {
            } else {
                $.alert(data.message);
            }
        });
    });

    var init = function () {
        var $table = $('#table');
        var $table_work = $('#table_work');
        $table.bootstrapTable({
            method: 'get',
            toolbar: '#toolbar',//指定工具栏
            striped: true, //是否显示行间隔色
            mobileResponsive: true,
            showExport: true,
            sortName: $table.attr('data-url') == '/user/rank-balances-page' ? 'total' : 'id',
            sortOrder: 'desc',
            dataField: "data",
            iconsPrefix: "fa",
            pageNumber: 1, //初始化加载第一页，默认第一页
            pagination: true,//是否分页
            queryParamsType: 'limit',//查询参数组织方式
            queryParams: queryParams,//请求服务器时所传的参数
            sidePagination: 'server',//指定服务器端分页
            slientSort: true,//指定服务器端分页
            pageSize: 20,//单页记录数
            pageList: [10, 20, 50, 100, 200],//分页步进值
            showRefresh: true,//刷新按钮
            showColumns: true,
            clickToSelect: true,//是否启用点击选中行
            paginationShowPageGo: true,
        });
        $table_work.bootstrapTable({
            method: 'get',
            toolbar: '#toolbar',//指定工具栏
            striped: true, //是否显示行间隔色
            mobileResponsive: true,
            showExport: true,
            sortName: $table.attr('data-url') == '/user/rank-balances-page' ? 'total' : 'id',
            sortOrder: 'asc',
            dataField: "data",
            iconsPrefix: "fa",
            pageNumber: 1, //初始化加载第一页，默认第一页
            pagination: true,//是否分页
            queryParamsType: 'limit',//查询参数组织方式
            queryParams: queryParams,//请求服务器时所传的参数
            sidePagination: 'server',//指定服务器端分页
            slientSort: true,//指定服务器端分页
            pageSize: 20,//单页记录数
            pageList: [10, 20, 50, 100, 200],//分页步进值
            showRefresh: true,//刷新按钮
            showColumns: true,
            clickToSelect: true,//是否启用点击选中行
            paginationShowPageGo: true,
        });
    };
    $.fn.bootstrapTable.init = init;
})(jQuery);
