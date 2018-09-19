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
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/element-ui/index.min.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/vue/vue.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/element-ui/index.min.js') }}"></script>
    <script>
        var arrTarget = {_blank: "新页面", _self: "当前页面"},
            arrStatus = {1: "启用", 2: "停用"},
            arrTypes = @json($types, 320),
            $vue_upload = null;
        $(function () {
            meTables({
                sTable: "#example2",
                searchType: "middle",
                checkbox: null,
                table: {
                    columns: [
                        {
                            title: "主键ID",
                            data: "id",
                            edit: {type: "hidden"},
                        },
                        {
                            title: "分类",
                            data: "type_id",
                            search: {type: "select"},
                            value: arrTypes,
                            edit: {
                                type: "select",
                                required: true,
                                number: true
                            },
                            render: function(data) {
                                return getValue(arrTypes, data);
                            }
                        },
                        {
                            title: "名称",
                            data: "title",
                            search: {type: "text", name: "title:like"},
                            edit: {
                                type: "text",
                                required: true,
                                rangelength: [2, 50]
                            }
                        },
                        {
                            title: "描述",
                            data: "description",
                            edit: {
                                type: "textarea",
                                required: true,
                                rows: 3,
                                rangelength: [2, 191]
                            }
                        },
                        {
                            title: "内容",
                            data: "content",
                            edit: {
                                type: "textarea",
                                minlength: 2
                            }
                        },
                        {
                            title: "图片",
                            data: "image",
                            edit: {
                                type: "vueUpload",
                                required: true,
                                action: "{{ url('admin/slide/slide/upload-image') }}",
                                rangelength: [2, 191]
                            },
                            render: function (data) {
                                return data ? '<img src="' + data + '" style="max-width:60px;">' : '没有上传图片';
                            }
                        },
                        {
                            title: "链接",
                            data: "url",
                            edit: {
                                type: "text",
                                required: true,
                                rangelength: [2, 191]
                            }
                        },
                        {
                            title: "打开方式",
                            data: "target",
                            value: arrTarget,
                            edit: {
                                type: "radio",
                                default: "_self",
                                required: true
                            }
                        },
                        {
                            title: "状态",
                            data: "status",
                            value: arrStatus,
                            edit: {
                                type: "radio",
                                default: 1,
                                required: true,
                                number: true
                            },
                            render: function (data) {
                                var c = data == 1 ? "green" : "red";
                                return '<span style="color:' + c + '">' + getValue(arrStatus, data) + '</span>';
                            }
                        },
                        {
                            title: "排序",
                            data: "sort",
                            edit: {
                                type: "text",
                                required: true,
                                value: 100,
                                number: true
                            }
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
                            orderable: false,
                            createdCell: meTables.handleOperator
                        }
                    ]
                }
            });

            $vue_upload = vueUpload("input[name=image][type=hidden]");
        });

        meTables.fn.extend({
            // 显示的前置和后置操作
            beforeShow: function (data, child) {
                if ($vue_upload.list.length > 0) {
                    $vue_upload.list.pop();
                }

                if (this.action === "update" && getValue(data, "image")) {
                    $vue_upload.list.push({
                        name: getValue(data, "name"),
                        url: getValue(data, "image")
                    })
                }

                return true;
            }
        });

    </script>
@endpush