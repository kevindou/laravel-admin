@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;" id="me-table-search-form-example2">
                            <button class="btn btn-success btn-sm pull-left me-table-button-example2"
                                    data-func="create">
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
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/element-ui/index.min.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/vue/vue.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/element-ui/index.min.js') }}"></script>
    <script>
        var arrStatus = @json($status, 320),
            arrRoles = @json($roles, 320),
            arrColors = {"10": "label-success", "0": "label-warning", "-1": "label-danger"};

        $(function () {
            var meTable = $("#example2").MeTables({
                checkbox: null,
                number: null,
                table: {
                    columns: [
                        {
                            title: "ID",
                            data: "id",
                            defaultOrder: "asc",
                            edit: {type: "hidden"}
                        },
                        {
                            title: "管理员名称",
                            data: "name",
                            sortable: false,
                            search: {name: "name:like"},
                            edit: {required: "true", rangelength: "[2, 50]"}
                        },
                        {
                            title: "管理员邮箱",
                            data: "email",
                            sortable: false,
                            search: {"type": "text"},
                            edit: {
                                required: "true", rangelength: "[2, 100]", email: true
                            }
                        },
                        {
                            title: "管理员密码",
                            data: "password",
                            sortable: false,
                            hide: true,
                            view: false,
                            edit: {type: "password", rangelength: "[6, 20]"}
                        },
                        {
                            title: "管理员头像",
                            data: "avatar",
                            sortable: false,
                            edit: {
                                type: "vueUpload",
                                required: true,
                                action: "{{ url('admin/admins/upload-image') }}",
                                rangelength: [2, 191]
                            },
                            render: function (data) {
                                return data ? '<img src="' + data + '" style="max-width:60px;">' : '没有上传图片';
                            }
                        },
                        {
                            title: "状态",
                            data: "status",
                            sortable: false,
                            render: function (data) {
                                return '<span class="label ' + getValue(arrColors, data, 'label-info') + '">' + getValue(arrStatus, data, data) + '</span>';
                            },
                            value: arrStatus,
                            edit: {type: "radio", "default": 10, "required": true, "number": true}
                        },
                        {
                            title: "角色",
                            data: null,
                            sortable: false,
                            hide: true,
                            value: arrRoles,
                            view: false,
                            edit: {
                                name: "role_ids[]",
                                type: "checkbox",
                                required: true,
                                number: true
                            }
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

            var $vue_upload = vueUpload("input[name=avatar][type=hidden]");

            $.extend(meTable, {
                beforeShow: function (data) {
                    var method = this.action === "create" ? "show" : "hide";
                    $(".div-right-role_ids").parent()[method]();

                    if ($vue_upload.list.length > 0) {
                        $vue_upload.list.pop();
                    }

                    if (this.action === "update" && getValue(data, "avatar")) {
                        $vue_upload.list.push({
                            name: getValue(data, "name"),
                            url: getValue(data, "avatar")
                        })
                    }
                }
            });
        })
    </script>
@endpush