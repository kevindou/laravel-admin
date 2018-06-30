@push('style')
    @if (isset($tableCss) && $tableCss)
        @foreach($tableCss as $css)
            <link rel="stylesheet" type="text/css" href="{{ asset($css) }}"/>
        @endforeach
    @endif
@endpush
@push("script")
    @if (isset($tableJavascript) && $tableJavascript)
        @foreach($tableJavascript as $url)
            <script src="{{ asset($url) }}"></script>
        @endforeach
    @endif
@endpush