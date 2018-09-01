@extends('smsprovider::layouts.master')

@section('content')


    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div class="col-md-12">
                <h1 class="pager text-primary">{{ trans('smsprovider::smsgateway.new_temps_title') }}</h1>
                <hr>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <form action="{{ route('smsprovider.providers.store-user-templates') }}" method="post">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">

                    <div id="additional_inputs">
                        <div class="col-md-10 col-md-offset-1" style="margin-bottom: 1em;">
                            <div class="row" id="add_raw_mody">
                                <div class="col-md-3">
                                    <div class="form-inline">
                                        <label>{{ trans('smsprovider::smsgateway.title') }}</label>
                                        <input type="text" required name="title[]" class="form-control add_name">
                                    </div>

                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>{{ trans('smsprovider::smsgateway.message') }}</label>
                                        <textarea required rows="3" name="message[]" class="form-control add_value"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a style="margin-top:1.7em;" href="javascript:void(0)"
                                       class="btn btn-danger delete_add_raw" data-id="mody">
                                        {{ trans('smsprovider::smsgateway.delete') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <a href="javascript:void(0)" id="new_additional"
                               class="btn btn-info col-md-2">{{ trans('smsprovider::smsgateway.add') }}</a>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6 col-md-offset-2">
                                <hr>
                                <button type="submit"
                                        class="btn btn-primary">{{ trans('smsprovider::smsgateway.save') }}</button>
                                <button type="reset"
                                        class="btn btn-warning">{{ trans('smsprovider::smsgateway.erase') }} </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('styles')

@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#new_additional', function () {
                var random = Math.random().toString(36).substring(7);
                $('#additional_inputs').append('<div class="col-md-10 col-md-offset-1"  style="margin-bottom: 1em;">\n' +
                    '            <div class="row" id="add_raw_' + random + '">\n' +
                    '                <div class="col-md-3">\n' +
                    '                    <div class="form-inline">\n' +
                    '                        <label>' + '{{ trans('smsprovider::smsgateway.title') }}' + '</label>\n' +
                    '                        <input required type="text" name="title[]" class="form-control">\n' +
                    '                    </div>\n' +
                    '\n' +
                    '                </div>\n' +
                    '                <div class="col-md-7">\n' +
                    '                    <div class="form-group">\n' +
                    '                        <label>' + '{{ trans('smsprovider::smsgateway.message') }}' + '</label>\n' +
                    '                        <textarea required rows="3" type="text" name="message[]" class="form-control"></textarea>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '                <div class="col-md-2">\n' +
                    '                    <a href="javascript:void(0)" class="btn btn-danger delete_add_raw" data-id="' + random + '">' + '{{ trans('smsprovider::smsgateway.delete') }}' + '</a>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>\n');
            });
        });

        $(document).on('click', '.delete_add_raw', function () {
            var id = $(this).data('id');
            $(document).find('#add_raw_' + id).remove();
        });
    </script>
@stop