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
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
@include('admin::common.datatable')
@push("script")
    <script>
        $(function () {
            var meTable = meTables({
                title: "",
                sTable: "#example2",
                searchType: "middle",
                table: {
                    columns: [
                        {
                            "title": "id",
                            "data": "id",
                            "edit": {"type": "hidden"},
                            "defaultOrder": "asc"
                        },
                        {
                            "title": "权限名称",
                            "data": "name",
                            "orderable": false,
                            "search": {type: "text", name: "name:like"},
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {
                            "title": "权限说明",
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
                            "orderable": false,
                            "edit": {
                                "required": true, "rangelength": "[2, 190]"
                            }
                        },
                        {"title": "创建时间", "data": "created_at"},
                        {"title": "修改时间", "data": "updated_at"},
                        {
                            "title": "操作", "data": null, "orderable": false,
                            "createdCell": meTables.handleOperator
                        }
                    ]
                }
            });
        })
    </script>
@endpush