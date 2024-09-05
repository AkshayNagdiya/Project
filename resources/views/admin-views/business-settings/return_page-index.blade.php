@extends('layouts.admin.app')

@section('title', translate('Return Policy'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/pages.png')}}" alt="{{ translate('pages') }}">
                {{translate('pages')}}
            </h2>
        </div>

        <div class="inline-page-menu mb-4">
            @include('admin-views.business-settings.partial.page-nav')
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.business-settings.return_page_update')}}" method="post">
                    @csrf

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label for="switcher_input" class="text-dark font-weight-bold mb-0">{{ translate('Check Status') }}</label>
                        <label class="switcher">
                            <input type="checkbox" id="switcher_input" class="switcher_input" name="status" value="1" {{ json_decode($data['value'],true)['status']==1?'checked':''}}>
                            <span class="switcher_control"></span>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="ckeditor form-control" name="content">
                                    {{ json_decode($data['value'],true)['content']}}
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                            class="btn btn-primary demo-form-submit">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('script_2')
@endpush


@push('script_2')
<script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
