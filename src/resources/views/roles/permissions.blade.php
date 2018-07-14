@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <form role="form" action="{{ url('/admin/roles/update-permissions?id='.array_get($role, 'id')) }}"
              method="post">
            {{ csrf_field() }}
            <div class="col-md-3">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">角色信息</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="input-name"> 角色名称 </label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ array_get($role, 'name') }}" id="input-name" placeholder="角色名称">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="input-description">角色说明</label>
                            <textarea name="description" class="form-control" id="input-description"
                                      placeholder="角色说明">{{ array_get($role, 'description') }}</textarea>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="input-display-name">显示名称</label>
                            <input type="text" name="display_name" class="form-control" id="input-display-name"
                                   value="{{ array_get($role, 'display_name') }}" placeholder="显示名称">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('display_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">角色信息</h3>
                    </div>
                    <div class="box-body">
                        <div id="tree-one" class="tree tree-selectable"></div>
                        <input type="hidden" name="menu_ids" id="menu_ids" value="">
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> 权限信息 </h3>
                    </div>
                    <div class="box-body">
                        @foreach($permissions as $value)
                            <label>
                                <input type="checkbox" name="permissions[]"
                                       value="{{ array_get($value, 'id') }}"
                                       @if (in_array(array_get($value, 'id'), $hasIds))
                                       checked="checked"
                                        @endif
                                >
                                {{ array_get($value, 'description') }} ({{ array_get($value, 'name') }})
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/jstree/default/style.min.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jstree/jstree.min.js') }}"></script>
    <script>
        function getChildrenAttributes(data, parent_object) {
            var array_attributes = [], length = data.children.length;
            if (length > 0) {
                for (var i = 0; i < length; i++) {
                    var tmp_data = parent_object.instance.get_node(data.children[i]);
                    array_attributes.push.apply(array_attributes, getChildrenAttributes(tmp_data, parent_object));
                }
            } else if (data.data != null) {
                var array_data = data.data.split("/");

                if (array_data && array_data.length > 0) {
                    array_data.pop();
                }

                if (array_data.length > 0) {
                    array_attributes.push(array_data.join("/"));
                }
            }

            return array_attributes;
        }

        var strCurrentUrl = '/admin/roles/index';
        $(function () {
            $("input[type=checkbox]").iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $("#tree-one").jstree({
                "plugins": ["checkbox"],
                core: {
                    "animation": 0,
                    "check_callback": true,
                    data: @json($trees, 320)
                }
            }).on("changed.jstree", function (e, data) {
                $("#menu_ids").val(data.selected.join(","))
                if (data.action === "select_node" || data.action === "deselect_node") {
                    var isChecked = data.action === "select_node",
                        attributes = getChildrenAttributes(data.node, data);
                    attributes.forEach(function (attribute) {
                        $("input[value^='" + attribute + "/']").prop("checked", isChecked);
                    });
                }
            });
        })
    </script>
@endpush