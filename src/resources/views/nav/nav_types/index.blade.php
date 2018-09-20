@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-sm-12" id="me-table-search-form-example2">
                            <button class="btn btn-success btn-sm pull-left me-table-button-example2"
                                    data-func="create">
                                {{ trans('admin.create') }}
                            </button>
                        </div>
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
        var arrStatus = {1: "启用", 2: "停用"};
        $(function () {
            $("#example2").MeTables({
                number: false,
                checkbox: null,
                pk: "type_id",
                table: {
                    columns: [
                        {
                            title: "主键ID",
                            data: "type_id",
                            edit: {type: "hidden"}
                        },
                        {
                            title: "分类名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: true, rangelength: "[2, 100]"}
                        },
                        {
                            title: "分类说明",
                            data: "description",
                            sortable: false,
                            edit: {required: true, rangelength: "[2, 255]"}
                        },
                        {
                            title: "状态",
                            data: "status",
                            sortable: false,
                            value: arrStatus,
                            edit: {type: "radio", default: 1},
                            render: $.fn.meTables.statusRender
                        },
                        {
                            title: "创建时间",
                            data: "created_at"
                        },
                        {
                            title: "修改时间",
                            data: "updated_at"
                        },
                    ]
                }
            });
        });
    </script>
@endpush