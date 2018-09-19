@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-left" style="width: 100%">
                    <li class="active">
                        <a href="#revenue-chart" data-toggle="tab" aria-expanded="true">
                            {{ trans('网站基本配置') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="#sales-chart" data-toggle="tab" aria-expanded="false">
                            {{ trans('关于我的信息') }}
                        </a>
                    </li>
                    <li class="pull-right header">
                        <i class="fa fa-gear"></i>
                        {{ trans('配置信息') }}
                    </li>
                </ul>
                <div class="tab-content no-padding">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="revenue-chart">
                        <form class="form-horizontal">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">
                                        网站名称
                                        <span style="color:red">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="请输入名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">
                                        ICP备
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">
                                        公网安备份
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">
                                        统计代码
                                    </label>
                                    <div class="col-sm-6">
                                        <textarea name="" id="" class="form-control" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info">
                                    {{ trans('修改') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="chart tab-pane" id="sales-chart">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection