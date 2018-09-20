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
        var arr_status = {"1": "启用", "2": "停用"};
        $(function () {
            $("#example2").MeTables({
                pk: "type_id",
                table: {
                    columns: [
                        {
                            title: "主键ID",
                            data: "type_id",
                            edit: {type: "hidden"},
                        },
                        {
                            title: "名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: true, rangelength: [2, 100]}
                        },
                        {
                            title: "说明",
                            data: "description",
                            sortable: false,
                            edit: {required: true, rangelength: [2, 191]}
                        },
                        {
                            title: "排序",
                            data: "sort",
                            edit: {required: true, number: true, value: 100},
                        },
                        {
                            title: "状态",
                            data: "status",
                            value: arr_status,
                            sortable: false,
                            search: {type: "select"},
                            edit: {type: "radio", default: 1, required: true, number: true},
                            render: $.fn.meTables.statusRender
                        },
                        {
                            title: "创建时间",
                            data: "created_at",
                        },
                        {
                            title: "修改时间",
                            data: "updated_at",
                        }
                    ]
                }
            });
        });
    </script>
@endpush