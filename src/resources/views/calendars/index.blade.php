@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <div class="col-sm-12" id="me-table-search-form-me-table">
                        <button class="btn btn-success btn-sm pull-left me-table-create">
                            {{ trans('admin.create') }}
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="me-table" class="table table-bordered table-hover"></table>
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
            var table = $("#me-table").MeTables({
                title: "日程管理",
                number: null,
                table: {
                    columns: [
                        {
                            title: "id",
                            data: "id",
                            defaultOrder: "desc",
                            edit: {type: "hidden"}
                        },
                        {
                            title: "标题",
                            data: "title",
                            orderable: false,
                            search: {type: "text", name: "title:like"},
                            edit: {"rangelength": "[2, 255]", "required": true}
                        },
                        {
                            title: "说明",
                            data: "desc",
                            orderable: false,
                            search: {type: "text", name: "desc:like"},
                            edit: {
                                type: "textarea",
                                rangelength: "[2, 255]",
                                required: true
                            }
                        },
                        {
                            title: "开始时间",
                            data: "start",
                            edit: {
                                type: "dateTime",
                                required: true
                            }
                        },
                        {
                            title: "结束时间",
                            data: "end",
                            edit: {
                                type: "dateTime",
                                required: true
                            }
                        },
                        {
                            title: "状态",
                            data: "status",
                            hide: true,
                            value: @json($status, 320),
                            edit: {type: "radio", number: true, required: true}
                        },
                        {
                            title: "时间状态",
                            data: "time_status",
                            hide: true,
                            value: @json($timeStatus, 320),
                            edit: {
                                type: "radio",
                                number: true,
                                required: true
                            }
                        },
                        {
                            title: "背景颜色",
                            data: "style",
                            hide: true,
                            edit: {type: "color", number: true, required: true}
                        },
                        {
                            title: "创建时间",
                            data: "created_at"
                        },
                        {
                            title: "修改时间",
                            data: "updated_at"
                        },
                    ]
                }
            });

            // 颜色点击
            $("#style-select > li > a").click(function () {
                var color = $(this).css("color");
                $("#style-input").val(color);
                $(this).parent().parent().parent().prev("label").css("background-color", color);
            });
        })
    </script>
@endpush