<section class="content-header">
    <h1>
        {{ $title }} <small> {{ $description }} </small>
    </h1>
    @hasSection('header_right')
        <span class="pull-right" style="float:right;display: block;margin-top: -28px;position: relative">
            @yield('header_right')
        </span>
    @endif
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin/index/index') }}">
                <i class="fa fa-dashboard"></i> {{ trans('admin.home') }}
            </a>
        </li>
        @if (isset($breadCrumb) && is_array($breadCrumb))
            @foreach ($breadCrumb as $item)
                @if ($loop->last)
                    <li class="active">{{ $item['name'] }}</li>
                @else
                    <li><a href="{{ url($item['url']) }}">{{ $item['name']  }}</a></li>
                @endif
            @endforeach
        @endif
    </ol>
</section>

