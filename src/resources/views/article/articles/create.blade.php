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
        <form action="{{ url('/admin/article/articles/store') }}" method="post" id="create-form">
            {{ csrf_field() }}
            @include('admin.article.articles._form', ['info' => [
            'status' => 1, 'recommend' => 2, 'author' => '金星', 'sort' => 100
            ]])
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body" id="body-content">
                        <div class="form-group">
                            <label for="title">
                                {{ trans('文章格式') }}
                                <span class="text-danger">*</span>
                            </label>
                            <?php $formats = [1 => 'Html', 2 => 'Markdown']; ?>
                            @foreach ($formats as $format => $label)
                            <div class="radio">
                                <label>
                                    <input type="radio"
                                           @if(old('format', 2) == $format) checked @endif
                                           name="format" value="{{ $format }}" >{{ $label }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="format-1 hide format-input">
                            <textarea id="editor"></textarea>
                        </div>
                        <div id="editormd_id" class="format-2 format-input">
                            <textarea id="editor-id"></textarea>
                        </div>
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
            <input type="hidden" name="content" value="" id="content">
        </form>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/element-ui/index.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    {!! editor_css() !!}
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
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/vue/vue.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/element-ui/index.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/jquery-validation/validate.message.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    {!! editor_js() !!}
    <script>
        $(function () {
            var $vue_upload = vueUpload("input[name=thumb_image][type=hidden]");

            // 点击显示隐藏
            $("input[name=format]").change(function () {
                $(".format-input").addClass("hide");
                $(".format-" + $(this).val()).removeClass("hide");
            });

            $("#editor").wysihtml5();
            // 表单提交之前
            $("#create-form").submit(function () {
                var content = $("input[name=format]:checked").val() == 2 ? $("#editor-id").val() : $("#editor").val();
                $("#content").val(content);
                return $(this).validate().form();
            });
        });
    </script>
@endpush