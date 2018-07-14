@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
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
        var super_role_id = '{{ config('admin.super_role_id') }}';
        function handleOperator(td, data, rowData, row) {
            var attr = "data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"";
            var html = "<button class='btn btn-info btn-xs me-table-update' " + attr + " ><i class='fa fa-edit'></i></button> ";
            if (rowData["id"] != super_role_id) {
                html += "<button class='btn btn-danger btn-xs me-table-delete' " + attr + "><i class='fa fa-trash'></i></button> ";
                html += "<a class=\"btn btn-info btn-xs\" href=\"{{ url('admin/roles/permissions') }}?id=" + rowData["id"] + "\">" +
                    "<i class='fa fa-leaf'></i> 分配角色</a> ";
                html += "<a class=\"btn btn-warning btn-xs\" href=\"{{ url('admin/roles/menus') }}?id=" + rowData["id"] + "\">" +
                    "<i class='fa fa-edit'></i> 分配菜单 </a> ";
            }

            $(td).html(html);
        }

        $(function () {
            var meTable = meTables({
                "sTable": "#example2",
                "searchType": "middle",
                checkbox: null,
                "table": {
                    columns: [
                        {
                            "title": "id",
                            "data": "id",
                            "edit": {"type": "hidden"},
                            "defaultOrder": "asc"
                        },
                        {
                            "title": "角色名称",
                            "data": "name",
                            "orderable": false,
                            "search": {type: "text"},
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {
                            "title": "角色说明",
                            "data": "description",
                            "orderable": false,
                            "search": {type: "text"},
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {
                            "title": "显示名称",
                            "data": "display_name",
                            "search": {type: "text"},
                            "orderable": false,
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {"title": "创建时间", "data": "created_at"},
                        {"title": "修改时间", "data": "updated_at"},
                        {
                            "title": "操作",
                            "data": null,
                            "orderable": false,
                            "createdCell": handleOperator
                        }
                    ]
                }
            });
        })
    </script>
@endpush