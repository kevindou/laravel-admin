@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="col-sm-12" id="me-table-search-form-example2">
                        <button class="btn btn-success btn-sm pull-left me-table-button-example2" data-func="create">
                            {{ trans('admin.create') }}
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin::common.datatable')
@push("script")
    <script>
        $.extend($.fn.meTables, {
            menusCreate: function (params) {
                return '<select ' + this.handleParams(params) + '>' +
                    '<option value="0">顶级分类</option>{!! $group !!}</select>';
            },
            menusSearchMiddleCreate: function (params, value, defaultObject) {
                params["id"] = "search-" + params.name;
                params.class = params.class || "form-control";
                return '<div class="input-group input-group-sm">' +
                    '<select ' + this.handleParams(params) + '>' +
                    '<option value="">请选择分类</option>' +
                    '<option value="0">顶级分类</option>{!! $group !!}' +
                    '</select></div> ';
            },
        });

        var arrTarget = {_blank: "新页面", _self: "当前页面"},
            arrStatus = {1: "启用", 2: "停用"},
            arrTypes = @json($types, 320),
            arrParents = @json(array_pluck($parents, null, 'id'), 320);
        arrParents[0] = {"name": "顶级分类"};
        $(function () {
            $("#example2").MeTables({
                number: false,
                checkbox: null,
                table: {
                    columns: [
                        {
                            title: "ID",
                            data: "id",
                            edit: {type: "hidden"}
                        },
                        {
                            title: "父级分类",
                            data: "parent_id",
                            sortable: false,
                            search: {type: "menus"},
                            edit: {type: "menus", number: true},
                            render: function (data) {
                                return getValue(arrParents, data + ".name", data);
                            }
                        },
                        {
                            title: "导航分类",
                            data: "type_id",
                            sortable: false,
                            value: arrTypes,
                            search: {type: "select"},
                            edit: {type: "select", required: true, number: true},
                            render: function (data) {
                                return getValue(arrTypes, data);
                            }
                        },
                        {
                            title: "导航名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: true, rangelength: "[2, 100]"}
                        },
                        {
                            title: "导航地址",
                            data: "url",
                            sortable: false,
                            edit: {required: true, rangelength: "[1, 100]"}
                        },
                        {
                            title: "导航图标",
                            data: "icon",
                            sortable: false,
                            edit: {rangelength: "[2, 10]"}
                        },
                        {
                            title: "打开方式",
                            data: "target",
                            sortable: false,
                            value: arrTarget,
                            edit: {type: "radio", default: "_self", required: true}
                        },
                        {
                            title: "状态",
                            data: "status",
                            sortable: false,
                            value: arrStatus,
                            edit: {type: "radio", default: 1, required: true},
                            render: $.fn.meTables.statusRender
                        },
                        {
                            title: "排序",
                            data: "sort",
                            edit: {value: 100, number: true, required: true}
                        },
                        {
                            title: "创建时间",
                            data: "created_at"
                        },
                        {
                            title: "修改时间",
                            data: "updated_at"
                        }
                    ]
                }
            });
        });
    </script>
@endpush