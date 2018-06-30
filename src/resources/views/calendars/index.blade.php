@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-header with-border">
                    <div class="col-sm-12">
                        <button class="btn btn-success btn-sm pull-left me-table-show-table-create">
                            {{ trans('admin.create') }}
                        </button>
                        <form class="form-inline pull-right" id="search-form" name="search-form">
                            <div class="input-group input-group-sm">
                                <input type="text" name="desc" class="form-control" placeholder="说明">
                            </div>
                            <div class="input-group input-group-sm">
                                <input type="text" name="title" class="form-control" id="inputSearchTitle"
                                       placeholder="标题">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fa fa-search"></i>
                                        {{ trans('搜索') }}
                                    </button>
                                </div>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-btn">
                                    <button type="reset" class="btn btn-warning me-table-reset-table">
                                        {{ trans('清除') }}
                                    </button>
                                </div>
                            </div>
                        </form>
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
        function handleOperator(td, data, rowData, row) {
            var attr = "data-index=\"" + rowData["id"] + "\" data-row=\"" + row + "\"";
            var html = "<button class='btn btn-success btn-xs me-table-detail' " + attr + ">\
            <i class='fa fa-search'></i></button> ";
            html += "<button class='btn btn-info btn-xs me-table-update' " + attr + ">\
            <i class='fa fa-edit'></i></button> ";
            html += "<button class='btn btn-danger btn-xs me-table-delete' " + attr + ">\
            <i class='fa fa-trash'></i></button> ";
            $(td).html(html);
        }

        meTables.extend({
            "colorCreate": function (params) {
                return '<input type="hidden" name="style" value="#3c8dbc" id="style-input">\
                                <ul class="fc-color-picker color-chooser" id="style-select">\
                                    <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>\
                                    <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>\
                                </ul>';
            }
        });

        $(function () {
            var table = meTables({
                "title": "日程管理",
                "sTable": "#me-table",
                "table": {
                    dom: "t<'row'<'table-page col-sm-4'li><'col-sm-8'p>>",
                    columns: [
                        {"title": "id", "data": "id", "defaultOrder": "desc", "edit": {"type": "hidden"}},
                        {
                            "title": "标题", "data": "title", "orderable": false,
                            "edit": {"rangelength": "[2, 255]", "required": true}
                        },
                        {
                            "title": "说明", "data": "desc", "orderable": false,
                            "edit": {"type": "textarea", "rangelength": "[2, 255]", "rows": 5, "required": true}
                        },
                        {
                            "title": "开始时间", "data": "start",
                            "edit": {"type": "dateTime", "required": true}
                        },
                        {
                            "title": "结束时间", "data": "end",
                            "edit": {"type": "dateTime", "required": true}
                        },
                        {
                            "title": "状态", "data": "status", "hide": true,
                            "value": @json($status, 320),
                            "edit": {"type": "radio", "number": true, "required": true}
                        },
                        {
                            "title": "时间状态", "data": "time_status", "hide": true,
                            "value": @json($timeStatus, 320),
                            "edit": {"type": "radio", "number": true, "required": true}
                        },
                        {
                            "title": "背景颜色", "data": "style", "hide": true,
                            "edit": {"type": "color", "number": true, "required": true}
                        },
                        {"title": "创建时间", "data": "created_at"},
                        {"title": "修改时间", "data": "updated_at"},
                        {
                            "width": "80px",
                            "title": "操作",
                            "data": null,
                            "orderable": false,
                            "createdCell": handleOperator
                        }
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