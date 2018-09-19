@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;" id="me-table-search-form">
                            <button class="btn btn-success btn-sm pull-left me-table-create">
                                {{ trans('admin.create') }}
                            </button>
                        </div>
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
        mt.extend({
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

            var meTable = meTables({
                sTable: "#example2",
                searchType: "middle",
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
                            orderable: false,
                            search: {type: "menus"},
                            edit: {
                                type: "menus",
                                number: true
                            },
                            render: function (data) {
                                return getValue(arrParents, data + ".name", data);
                            }
                        },
                        {
                            title: "导航分类",
                            data: "type_id",
                            orderable: false,
                            value: arrTypes,
                            search: {type: "select"},
                            edit: {
                                type: "select",
                                required: true,
                                number: true
                            },
                            render: function (data) {
                                return getValue(arrTypes, data);
                            }
                        },
                        {
                            title: "导航名称",
                            data: "name",
                            orderable: false,
                            search: {type: "text", name: "name:like"},
                            edit: {
                                type: "text",
                                required: true,
                                rangelength: "[2, 100]"
                            }
                        },
                        {
                            title: "导航地址",
                            data: "url",
                            orderable: false,
                            edit: {
                                type: "text",
                                required: true,
                                rangelength: "[1, 100]"
                            }
                        },
                        {
                            title: "导航图标",
                            data: "icon",
                            orderable: false,
                            edit: {
                                type: "text",
                                rangelength: "[2, 10]"
                            }
                        },
                        {
                            title: "打开方式",
                            data: "target",
                            orderable: false,
                            value: arrTarget,
                            edit: {
                                type: "radio",
                                default: "_self",
                                required: true
                            }
                        },
                        {
                            title: "状态",
                            data: "status",
                            orderable: false,
                            value: arrStatus,
                            edit: {
                                type: "radio",
                                default: 1,
                                required: true
                            },
                            render: function (data) {
                                var c = data == 1 ? "green" : "red";
                                return '<span style="color:' + c + '">' + getValue(arrStatus, data) + '</span>';
                            }
                        },
                        {
                            title: "排序",
                            data: "sort",
                            edit: {
                                type: "text",
                                value: 100,
                                number: true,
                                required: true
                            }
                        },
                        {
                            title: "创建时间",
                            data: "created_at"
                        },
                        {
                            title: "修改时间",
                            data: "updated_at"
                        },
                        {
                            title: "操作",
                            data: null,
                            orderable: false,
                            "createdCell": meTables.handleOperator
                        }
                    ]
                }
            });
        });
    </script>
@endpush