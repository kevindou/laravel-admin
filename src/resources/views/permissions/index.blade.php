@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-sm-12" id="me-table-search-form-example2">
                            <button class="btn btn-success btn-sm pull-left me-table-button-example2" data-func="create">
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
        $(function () {
            var meTable = $("#example2").meTables({
                title: "权限",
                number: false,
                table: {
                    columns: [
                        {
                            title: "id",
                            data: "id",
                            edit: {"type": "hidden"},
                            defaultOrder: "asc"
                        },
                        {
                            title: "权限名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: true, rangelength: "[2, 190]"}
                        },
                        {
                            title: "权限说明",
                            data: "description",
                            sortable: false,
                            search: {type: "text"},
                            edit: {required: true, rangelength: "[2, 190]"}
                        },
                        {
                            title: "显示名称",
                            data: "display_name",
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
                        }
                    ]
                }
            });
        })
    </script>
@endpush