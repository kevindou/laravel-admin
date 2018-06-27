<ol class="breadcrumb">
    <li>
        <a href="{{ url('admin/index/index') }}">
            <i class="fa fa-dashboard"></i> {{ trans('admin.home') }}
        </a>
    </li>
    @foreach ($breadCrumb as $item)
        @if ($loop->last)
            <li class="active">{{ $item['name'] }}</li>
        @else
            <li><a href="{{ url($item['url']) }}">{{ $item['name']  }}</a></li>
        @endif
    @endforeach
</ol>