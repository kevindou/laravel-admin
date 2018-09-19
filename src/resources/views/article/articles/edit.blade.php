@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        @if($errors->all())
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> {{ trans('表单数据存在问题') }}!</h4>
                    @foreach ($errors->all() as $message)
                        {{ $message }} <br/>
                    @endforeach
                </div>
            </div>
        @endif
        <form action="{{ url('/admin/article/articles/update') }}" method="post" id="update-form">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ array_get($info, 'id') }}">
            @include('admin::article.articles._form', compact('info'))
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"> {{ trans('文章内容') }} </h3>
                    </div>
                    <div class="box-body">
                        @if($info['format'] == 1)
                            <div>
                            <textarea id="editor" name="content" rows="10"
                                      cols="80">{{ old('content', $info['content']) }}</textarea>
                            </div>
                        @else
                            <div id="editormd_id">
                                <textarea name="content">{{ old('content', $info['content']) }}</textarea>
                            </div>
                        @endif
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">
                                {{ trans('重置') }}
                            </button>

                            <button type="submit" class="btn btn-primary">
                                {{ trans('提交') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/element-ui/index.min.css') }}">
    @if($info['format'] == 2)
        {!! editor_css() !!}
    @else
        <link rel="stylesheet"
              href="{{ asset('admin-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <style>
            #editor {
                width: 100%;
                height: 200px;
                font-size: 14px;
                line-height: 18px;
                border: 1px solid #dddddd;
                padding: 10px;
            }
        </style>
    @endif
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/vue/vue.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/element-ui/index.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jquery-validation/validate.message.js') }}"></script>
    @if($info['format'] == 1)
        <script src="{{ asset('admin-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    @else
        {!! editor_js() !!}
    @endif
    <script>
        $(function () {
            $vue_upload = vueUpload("input[name=thumb_image][type=hidden]");
            @if($info['thumb_image'])
            $vue_upload.list.push({
                name: "{{ $info['title'] }}",
                url: "{{ $info['thumb_image'] }}"
            });
            @endif
            @if($info['format'] == 1)
            $("#editor").wysihtml5();
            @endif

            // 表单提交处理
            $("#update-form").submit(function () {
                return $(this).validate().form();
            });
        });
    </script>
@endpush