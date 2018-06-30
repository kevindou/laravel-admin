@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;">
                            <button id="create" class="btn btn-success btn-sm pull-left">
                                {{ trans('admin.create') }}
                            </button>

                            <form class="form-inline pull-right" id="search-form" name="searchForm">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="description" class="form-control" placeholder="权限说明">
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="display_name" class="form-control" placeholder="显示名称">
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="name" class="form-control" placeholder="权限名称">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa fa-search"></i>
                                            {{ trans('搜索') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover"></table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('admin-assets/plugins/datatables/DataTables-1.10.12/css/dataTables.bootstrap.min.css') }}"/>
    <style>
        div.table-page div {
            float: left;
        }

        div.dataTables_length {
            margin-right: 5px;
            line-height: 35px;
        }
    </style>
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/datatables/DataTables-1.10.12/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/datatables/DataTables-1.10.12/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/table/table.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            var meTable = meTables({
                title: "",
                "sTable": "#example2",
                table: {
                    dom: "t<'row'<'table-page col-sm-4'li><'col-sm-8'p>>",
                    columns: [
                        {"title": "id", "data": "id", "edit": {"type": "hidden"}, "defaultOrder": "asc"},
                        {
                            "title": "权限名称", "data": "name", "orderable": false, "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {
                            "title": "权限说明", "data": "description", "orderable": false, "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {
                            "title": "显示名称",
                            "data": "display_name",
                            "orderable": false,
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {"title": "创建时间", "data": "created_at"},
                        {"title": "修改时间", "data": "updated_at"},
                        {
                            "title": "操作", "data": null, "orderable": false,
                            "createdCell": function (td, data, rowData, row, col) {
                                var attr = "data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"";
                                var html = "<button class='btn btn-info btn-xs example2-update' " + attr + "><i class='fa fa-edit'></i></button> ";
                                html += "<button class='btn btn-danger btn-xs example2-delete' " + attr + "><i class='fa fa-trash'></i></button>";
                                $(td).html(html);
                            }
                        }
                    ]
                }
            });


            // 表单新增
            $("#create").click(function (e) {
                e.preventDefault();
                meTable.create();
            });
        })
    </script>
@endpush