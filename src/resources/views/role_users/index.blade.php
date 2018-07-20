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
        var admins = @json($admins, 320),
            roles = @json($roles, 320);

        $(function () {
            var meTable = meTables({
                sTable: "#example2",
                searchType: "middle",
                checkbox: null,
                table: {
                    columns: [
                        {
                            "title": "管理员信息",
                            "data": "user_id",
                            "orderable": false,
                            "search": {"type": "select"},
                            value: admins,
                            "edit": {
                                type: "select",
                                required: "true",
                                number: true
                            },
                            "createdCell": function (td, data) {
                                $(td).html(admins[data] || data);
                            }
                        },
                        {
                            "title": "角色信息",
                            "data": "role_id",
                            "orderable": false,
                            "search": {"type": "select"},
                            value: roles,
                            "edit": {
                                type: "select",
                                required: "true",
                                number: true
                            },
                            "createdCell": function (td, data) {
                                $(td).html(roles[data] || data);
                            }
                        },
                        {
                            "title": "操作",
                            "data": null,
                            "orderable": false,
                            "createdCell": function (td, data, rowData, row) {
                                var attr = "data-index=\"" + meTables.fn.options.pk + "\" data-row=\"" + row + "\"";
                                var html = "<button class='btn btn-danger btn-xs role-user-delete' " + attr + ">\
                                    <i class='fa fa-trash'></i></button>";
                                $(td).html(html);
                            }
                        }
                    ]
                }
            });
        })
    </script>
@endpush