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
        var arrStatus = {1: "启用", 2: "停用"};
        $(function () {
            var meTable = meTables({
                sTable: "#example2",
                searchType: "middle",
                checkbox: null,
                pk: "type_id",
                table: {
                    columns: [
                        {title: "主键ID", data: "type_id", edit: {type: "hidden"}},
                        {
                            title: "分类名称",
                            data: "name",
                            orderable: false,
                            search: {name: "name:like"},
                            edit: {
                                type: "text",
                                required: true,
                                ranglength: "[2, 100]"
                            }
                        },
                        {
                            title: "分类说明",
                            data: "description",
                            orderable: false,
                            edit: {
                                type: "text",
                                required: true,
                                ranglength: "[2, 255]"
                            }
                        },
                        {
                            title: "状态",
                            data: "status",
                            orderable: false,
                            value: arrStatus,
                            search: {type: "select"},
                            edit: {
                                type: "radio",
                                default: 1
                            },
                            render: function (data) {
                                var c = data == 1 ? "green" : "red";
                                return '<span style="color:' + c + '">' + getValue(arrStatus, data) + '</span>';
                            }
                        },
                        {title: "创建时间", data: "created_at"},
                        {title: "修改时间", data: "updated_at"},
                        {
                            title: "操作",
                            data: null,
                            orderable: false,
                            createdCell: meTables.handleOperator
                        }
                    ]
                }
            });
        });
    </script>
@endpush