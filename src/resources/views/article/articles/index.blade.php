@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-sm-12" id="me-table-search-form-example2">
                            <a class="btn btn-success btn-sm pull-left"
                               href="{{ url('admin/article/articles/create') }}">
                                {{ trans('添加') }}
                            </a>
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
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/element-ui/index.min.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/vue/vue.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/element-ui/index.min.js') }}"></script>
    <script>
        var arr_status = {"1": "启用", "2": "停用"},
            arr_types = @json($types, 320),
            $vue_upload = null;
        $(function () {
            var table = $("#example2").MeTables({
                operations: null,
                table: {
                    columns: [
                        {
                            title: "ID",
                            data: "id",
                            edit: {type: "hidden"},
                            defaultOrder: "desc"
                        },
                        {
                            title: "分类",
                            data: "type_id",
                            sortable: false,
                            value: arr_types,
                            search: {type: "select"},
                            edit: {
                                type: "select",
                                required: true,
                                number: true
                            },
                            render: function (data) {
                                return getValue(arr_types, data, data)
                            }
                        },
                        {
                            title: "作者",
                            data: "author",
                            hide: true,
                            sortable: false,
                            edit: {required: true, rangelength: [2, 20], value: "金星"}
                        },
                        {
                            title: "标题",
                            data: "title",
                            sortable: false,
                            search: {name: "title:like"},
                            edit: {required: true, rangelength: [2, 100]}
                        },
                        {
                            title: "关键词",
                            data: "keywords",
                            search: {name: "keywords:like"},
                            sortable: false,
                            edit: {required: true, rangelength: [2, 150], placeholder: "多个请使用英文逗号(,)隔开"}
                        },
                        {
                            title: "摘要",
                            data: "excerpt",
                            sortable: false,
                            edit: {required: true, rangelength: [2, 500]}
                        },
                        {
                            title: "内容",
                            data: "content",
                            sortable: false,
                            hide: true,
                        },
                        {
                            title: "图片",
                            sortable: false,
                            data: "thumb_image",
                            edit: {
                                type: "vueUpload",
                                required: true,
                                action: "{{ url('admin/article/articles/upload-image') }}",
                                rangelength: [2, 191]
                            },
                            render: function (data) {
                                return data ? '<img src="' + data + '" style="max-width:60px;">' : '没有上传图片';
                            }
                        },
                        {
                            title: "浏览数",
                            data: "view_num"
                        },
                        {
                            title: "状态",
                            data: "status",
                            value: arr_status,
                            search: {type: "select"},
                            sortable: false,
                            edit: {
                                type: "radio",
                                required: true,
                                number: true,
                                default: 1
                            },
                            render: $.fn.meTables.statusRender
                        },
                        {
                            title: "创建时间",
                            data: "created_at",
                        },
                        {
                            title: "修改时间",
                            data: "updated_at",
                        },
                        {
                            title: "操作",
                            data: null,
                            width: "160px",
                            sortable: false,
                            createdCell: function (td, data, rowData, row) {
                                var attr = "data-index=\"id\" data-row=\"" + row + "\"";
                                var html = "<a target=\"_blank\" class='btn btn-success btn-xs' \
                                    href=\"/#article/" + rowData["id"] + "\">\
                                    <i class='fa fa-search'></i></a> ";
                                html += "<button class='btn btn-info btn-xs me-table-update-example2' " + attr + ">\
                                    <i class='fa fa-edit'></i></button> ";
                                html += "<a href=\"{{ url('/admin/article/articles/edit?id=') }}" + rowData["id"] + "\" \
                                            class='btn btn-warning btn-xs'> \
                                    <i class='fa fa-edit'></i>{{ trans('编辑详情') }}</a> ";
                                html += "<button class='btn btn-danger btn-xs me-table-delete-example2' " + attr + ">\
                                    <i class='fa fa-trash'></i></button> ";
                                $(td).html(html);
                            }
                        }
                    ]
                }
            });

            $vue_upload = vueUpload("input[name=thumb_image][type=hidden]");

            $.extend(table, {
                // 显示的前置和后置操作
                beforeShow: function (data, child) {
                    $vue_upload.list.pop();
                    if (this.action === "update" && getValue(data, "thumb_image")) {
                        $vue_upload.list.push({
                            name: getValue(data, "name"),
                            url: getValue(data, "thumb_image")
                        })
                    }

                    return true;
                }
            });
        });
    </script>
@endpush