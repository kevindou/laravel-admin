<div class="col-xs-6">
    <div class="box box-widget">
        <div class="box-header with-border">
            <h3 class="box-title"> {{ trans('主体信息') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <label for="title">
                    {{ trans('文章分类') }}
                    <span class="text-danger">*</span>
                </label>
                <select name="type_id" id="type_id" required="true" number="true" class="form-control">
                    <option value="">{{ trans('请选择文章分类') }}</option>
                    @foreach($types as $value => $label)
                        <option @if(old('type_id', array_get($info, 'type_id')) == $value) selected
                                @endif value="{{ $value }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">
                    {{ trans('文章标题') }}
                    <span class="text-danger">*</span>
                </label>
                <input type="text" required="true"
                       rangelength="[2, 100]" name="title"
                       class="form-control"
                       value="{{ old('title', array_get($info, 'title')) }}"
                       id="title" placeholder="请输入标题">
            </div>
            <div class="form-group">
                <label for="keywords">
                    {{ trans('关键字') }} ({{ trans('多个使用英文,逗号分割') }})
                    <span class="text-danger">*</span>
                </label>
                <input type="text" required="true" rangelength="[2, 100]" name="keywords"
                       class="form-control"
                       value="{{ old('keywords', array_get($info, 'keywords')) }}"
                       id="keywords" placeholder="请输入关键字">
            </div>
            <div class="form-group">
                <label for="excerpt">
                    {{ trans('摘要') }}
                    <span class="text-danger">*</span>
                </label>
                <textarea name="excerpt" id="excerpt"
                          class="form-control" required="true"
                          rangelength="[2, 100]" rows="3"
                          placeholder="请输入摘要">{{ old('excerpt', array_get($info, 'excerpt')) }}</textarea>
            </div>
            <div class="form-group">
                <label for="title">
                    {{ trans('文章作者') }}
                    <span class="text-danger">*</span>
                </label>
                <input type="text"
                       required="true"
                       rangelength="[2, 100]"
                       value="{{ old('author', array_get($info, 'author')) }}"
                       name="author" class="form-control"
                       id="title" placeholder="请输入标题">
            </div>
        </div>
    </div>
</div>
<div class="col-xs-6">
    <div class="box box-widget">
        <div class="box-header with-border">
            <h3 class="box-title"> {{ trans('附加信息') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <label for="sort">
                    {{ trans('图片') }}
                    <span class="text-danger">*</span>
                </label>
                <div id="vue-upload">
                    <el-upload
                            :headers="headers"
                            :limit="limit"
                            :disabled="disabled"
                            name="vue_image"
                            class="upload-demo"
                            action="{{ url('admin/article/articles/upload-image') }}"
                            :on-remove="remove"
                            :on-success="success"
                            :file-list="list"
                            list-type="picture">
                        <el-button size="small" type="primary"> 点击上传</el-button>
                        <div slot="tip" class="el-upload__tip"> 只能上传jpg/png文件，且不超过2M</div>
                    </el-upload>
                </div>

                <input type="hidden" name="thumb_image"
                       id="thumb_image"
                       value="{{ old('thumb_image', array_get($info, 'thumb_image')) }}"
                />
            </div>
            <div class="form-group">
                <label for="title">
                    {{ trans('是否推荐') }}
                    <span class="text-danger">*</span>
                </label>
                <?php $recommend = [1 => '推荐', 2 => '不推荐']; ?>
                @foreach ($recommend as $v => $label)
                    <div class="radio">
                        <label>
                            <input type="radio" name="recommend"
                                   @if(old('recommend', array_get($info, 'recommend')) == $v) checked
                                   @endif value="{{ $v }}">
                            {{ trans($label) }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <label for="title">
                    {{ trans('状态') }}
                    <span class="text-danger">*</span>
                </label>
                <?php $recommend = [1 => '开启', 2 => '禁用']; ?>
                @foreach ($recommend as $v => $label)
                    <div class="radio">
                        <label>
                            <input type="radio" name="status"
                                   @if(old('status', array_get($info, 'status')) == $v) checked
                                   @endif value="{{ $v }}">
                            {{ trans($label) }}
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                <label for="sort">
                    {{ trans('排序') }}
                    <span class="text-danger">*</span>
                </label>
                <input type="number" name="sort" id="sort"
                       value="{{ old('sort', array_get($info, 'sort')) }}"
                       class="form-control" number="true"/>
            </div>
        </div>
    </div>
</div>