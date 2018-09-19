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
        var super_role_id = '{{ config('admin.super_role_id') }}';

        function handleOperator(td, data, rowData, row) {
            var attr = "data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"";
            var html = "<button class='btn btn-info btn-xs me-table-update-example2' " + attr + " >" +
                "<i class='fa fa-edit'></i>" +
                "</button> ";
            if (rowData["id"] != super_role_id) {
                html += "<button class='btn btn-danger btn-xs me-table-delete-example2' " + attr + ">" +
                    "<i class='fa fa-trash'></i>" +
                    "</button> ";
                html += "<a class=\"btn btn-warning btn-xs\" href=\"{{ url('admin/roles/permissions') }}?id=" + rowData["id"] + "\">" +
                    "<i class='fa fa-leaf'></i> 分配权限&菜单 </a> ";
            }

            $(td).html(html);
        }


        $(function () {
            $("#example2").MeTables({
                checkbox: null,
                number: false,
                operations: null,
                table: {
                    columns: [
                        {
                            title: "id",
                            data: "id",
                            edit: {"type": "hidden"},
                            defaultOrder: "asc"
                        },
                        {
                            title: "角色名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: true, rangelength: "[2, 190]"}
                        },
                        {
                            title: "角色说明",
                            data: "description",
                            sortable: false,
                            search: {name: "description:like"},
                            edit: {required: true, rangelength: "[2, 190]"}
                        },
                        {
                            title: "显示名称",
                            data: "display_name",
                            search: {name: "display_name:like"},
                            sortable: false,
                            edit: {required: true, rangelength: "[2, 190]"}
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
                            sortable: false,
                            createdCell: handleOperator
                        }
                    ]
                }
            });
        })
    </script>
@endpush