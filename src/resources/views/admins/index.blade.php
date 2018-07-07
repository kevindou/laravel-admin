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
    <!-- Page specific script -->
    <script>
        function handleOperator(td, data, rowData, row) {
            var html = "<button class='btn btn-info btn-xs example2-update' data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"><i class='fa fa-edit'></i></button> ";
            html += "<button class='btn btn-danger btn-xs example2-delete' data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"><i class='fa fa-trash'></i></button> ";
            // html += "<a class='btn btn-info btn-xs' href=\"{{ url('admin/roles/permissions') }}/" + rowData["id"] + "\" data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"><i class='fa fa-leaf'></i> 分配角色</a> ";
            $(td).html(html);
        }

        var arrStatus = @json($status, 320),
            arrColors = {"10": "label-success", "0": "label-warning", "-1": "label-danger"};

        $(function () {
            var meTable = meTables({
                sTable: "#example2",
                searchType: "middle",
                table: {
                    columns: [
                        {
                            "title": "ID", "data": "id", "defaultOrder": "asc", "edit": {type: "hidden"}
                        },
                        {
                            "title": "管理员名称",
                            "data": "name",
                            "orderable": false,
                            "search": {"type": "text"},
                            "edit": {
                                required: "true", rangelength: "[2, 50]"
                            }
                        },
                        {
                            "title": "管理员邮箱",
                            "data": "email",
                            "orderable": false,
                            "search": {"type": "text"},
                            "edit": {
                                required: "true", rangelength: "[2, 100]", email: true
                            }
                        },
                        {
                            "title": "管理员密码", "data": "password", "orderable": false, hide: true,
                            "edit": {
                                type: "password", rangelength: "[6, 20]"
                            }
                        },
                        {"title": "管理员头像", "data": "avatar", "name": "avatar", "orderable": false},
                        {
                            "title": "状态", "data": "status", "orderable": false,
                            "render": function (data) {
                                return '<span class="label ' + getValue(arrColors, data, 'label-info') + '">' + getValue(arrStatus, data, data) + '</span>';
                            },
                            value: arrStatus,
                            edit: {type: "radio", "default": 10, "required": true, "number": true}
                        },
                        {"title": "创建时间", "data": "created_at"},
                        {"title": "修改时间", "data": "updated_at"},
                        {
                            "title": "操作", "data": null, "orderable": false,
                            "createdCell": handleOperator
                        }
                    ]
                }
            });
        })
    </script>
@endpush