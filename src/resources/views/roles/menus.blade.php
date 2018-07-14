@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <form role="form" action="{{ url('/admin/roles/update-menus?id='.array_get($role, 'id')) }}"
              method="post">
            {{ csrf_field() }}
            <div class="col-md-3">

            </div>

            <div class="col-md-9">

            </div>
        </form>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/iCheck/all.css') }}">
@endpush
@push("script")
    <script src="{{ asset('admin-assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        var strCurrentUrl = '/admin/roles/index';
        $(function () {

        })
    </script>
@endpush