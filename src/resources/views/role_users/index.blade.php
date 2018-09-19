@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <!-- /.box-header -->
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
        var admins = @json($admins, 320),
            roles = @json($roles, 320);
        $(function () {
            $("#example2").MeTables({
                number: false,
                checkbox: null,
                operations: {
                    buttons: {
                        see: null,
                        update: null,
                    }
                },
                table: {
                    columns: [
                        {
                            title: "管理员信息",
                            data: "user_id",
                            sortable: false,
                            search: {"type": "select"},
                            value: admins,
                            edit: {type: "select", required: "true", number: true},
                            render: function (data) {
                                return getValue(admins, data, data);
                            }
                        },
                        {
                            title: "角色信息",
                            data: "role_id",
                            sortable: false,
                            search: {"type": "select"},
                            value: roles,
                            edit: {type: "select", required: "true", number: true},
                            render: function (data) {
                                return getValue(roles, data, data);
                            }
                        }
                    ]
                }
            });
        })
    </script>
@endpush