@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;">
                            <button class="btn btn-success btn-sm pull-left me-table-create">
                                {{ trans('admin.create') }}
                            </button>
                            <form class="form-inline pull-right" id="search-form" name="searchForm">
                                <div class="form-group">
                                    <label class="sr-only">上级分类</label>
                                    <select name="parent" class="form-control">
                                        <option value="">请选择分类</option>
                                        <option value="0">顶级分类</option>
                                        {!! $group !!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="inputSearchName">名称</label>
                                    <input type="text" name="name" class="form-control" id="inputSearchName"
                                           placeholder="导航名称">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="inputSearchUrl">地址</label>
                                    <input type="text" name="url" class="form-control" id="inputSearchUrl"
                                           placeholder="导航地址">
                                </div>
                                <div class="form-group" style="min-width:200px;">
                                    <select class="form-control select2 pull-left" name="status[]" multiple="multiple"
                                            id="inputSearchStatus" data-placeholder="选择状态" style="width: 100%;">
                                        @foreach($status as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-search"></i>搜索
                                </button>
                                <button type="reset" class="btn btn-warning me-table-reset">
                                    {{ trans('清除') }}
                                </button>
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
@include('admin::common.datatable')
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/select2/select2.min.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        mt.extend({
            menusCreate: function (params) {
                return '<select ' + this.handleParams(params) + '><option value="0">顶级分类</option>{!! $group !!}</select>';
            }
        });
        $(function () {
            var arrParents = @json(array_pluck($parents, 'name', 'id'), 320);
            arrParents["0"] = "顶级分类";
            var arrStatus = @json($status, 320),
                arrColors = {"10": "label-success", "0": "label-warning", "-1": "label-danger"},
                table = meTables({
                    sTable: "#example2",
                    title: "导航栏目",
                    table: {
                        columns: [
                            {"title": "id", "data": "id", "edit": {type: "hidden"}, "defaultOrder": "asc"},
                            {
                                "title": "名称", "data": "name", "orderable": false,
                                "edit": {required: "true", rangelength: "[2, 50]"}
                            },
                            {
                                "title": "地址", "data": "url", "orderable": false,
                                "edit": {required: "true", rangelength: "[1, 255]"}
                            },
                            {
                                "title": "图标", "data": "icon", "orderable": false, "render": function (data) {
                                    return data ? "<i class=\"fa " + data + "\"></i>" : data;
                                },
                                "edit": {value: "fa-cube", required: "true", rangelength: "[2, 255]"}
                            },
                            {
                                "title": "父级名称", "data": "parent", "render": function (data) {
                                    return arrParents[data] ? arrParents[data] : "顶级分类";
                                },
                                "edit": {"type": "menus", "number": true}
                            },
                            {
                                "title": "状态", "data": "status", "render": function (data) {
                                    return '<span class="label ' + getValue(arrColors, data, 'label-info') + '">' + getValue(arrStatus, data, data) + '</span>';
                                },
                                value: @json($status, 320),
                                "edit": {"type": "radio", "number": true, "default": 10, "required": true}
                            },
                            {
                                "title": "排序", "data": "sort", "name": "sort",
                                "edit": {"number": true, value: 100}
                            },
                            {"title": "创建时间", "data": "created_at"},
                            {"title": "修改时间", "data": "updated_at"},
                            {
                                "title": "操作",
                                "data": null,
                                "orderable": false,
                                "createdCell": meTables.handleOperator
                            }
                        ]
                    }
                });

            $(".select2").select2();
        })
    </script>
@endpush