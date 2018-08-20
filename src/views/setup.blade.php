@extends('smsprovider::layouts.master')

@section('content')


    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div class="col-md-12">
                <h1 class="pager text-primary">{{ trans('smsprovider::smsgateway.config_title') }}</h1>
                <hr>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <form action="{{ route('smsprovider.submit_setup') }}" method="post">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    {{--{!! csrf_field() !!}--}}
                    <h2 class="title-page">{{ trans('smsprovider::smsgateway.account_settings') }}</h2>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username_column">{{ trans('smsprovider::smsgateway.username_column') }}</label>
                                <input type="text" name="username_column" id="username_column" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username_value">{{ trans('smsprovider::smsgateway.username_value') }}</label>
                                <input type="text" name="username_value" id="username_value" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="api_password_column">{{ trans('smsprovider::smsgateway.password_column') }}</label>
                                <input type="text" name="api_password_column" id="api_password_column"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="api_password_value">{{ trans('smsprovider::smsgateway.password_value') }}</label>
                                <input type="password" name="api_password_value" id="api_password_value"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h2 class="title-page">{{ trans('smsprovider::smsgateway.api_settings') }}</h2>

                    <div class="form-group">
                        <label for="api_company">{{ trans('smsprovider::smsgateway.api_company') }}</label>
                        <input type="text" name="api_company" id="api_company" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="api_url">{{ trans('smsprovider::smsgateway.api_url') }}</label>
                        <input type="text" name="api_url" id="api_url" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="api_method">{{ trans('smsprovider::smsgateway.http_method') }}</label>
                        <select name="api_method" id="api_method" class="form-control">
                            <option>get</option>
                            <option>post</option>
                            <option>put</option>
                            <option>patch</option>
                            <option>delete</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="api_destination">{{ trans('smsprovider::smsgateway.destination_attr') }}</label>
                        <input type="text" name="api_destination" id="api_destination" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="api_message">{{ trans('smsprovider::smsgateway.message_attr') }}</label>
                        <input type="text" name="api_message" id="api_message" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="api_success_code">{{ trans('smsprovider::smsgateway.success_code') }}</label>
                        <input type="text" name="api_success_code" id="api_success_code" class="form-control">
                    </div>

                    <div class="form-inline">
                        <label for="api_unicode" class="col-md-2"
                               style="margin-top: 0.85em;">{{ trans('smsprovider::smsgateway.use') }} UTF-8؟</label>
                        <input type="checkbox" name="api_unicode" style="width:2em;" id="api_unicode"
                               class="form-control">
                        {{ trans('smsprovider::smsgateway.utf-8') }}        </div>

                    <hr>
                    <div class="form-group">
                        <h2 class="title-page">{{ trans('smsprovider::smsgateway.add_params') }}</h2>

                        <div class="row">
                            <div class="col-md-8">
                                <a href="javascript:void(0)" id="new_additional"
                                   class="btn btn-info col-md-2">{{ trans('smsprovider::smsgateway.add') }}</a>
                            </div>
                        </div>
                        <div id="additional_inputs">
                            <div class="col-md-7 col-md-offset-1" style="margin-bottom: 1em;">
                                <div class="row" id="add_raw_mody">
                                    <div class="col-md-5">
                                        <div class="form-inline">
                                            <label>{{ trans('smsprovider::smsgateway.parameter') }}</label>
                                            <input type="text" name="api_add_name[]" class="form-control add_name">
                                        </div>

                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-inline">
                                            <label>{{ trans('smsprovider::smsgateway.value') }}</label>
                                            <input type="text" name="api_add_value[]" class="form-control add_value">
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
                $('#additional_inputs').append('<div class="col-md-7 col-md-offset-1"  style="margin-bottom: 1em;">\n' +
                    '            <div class="row" id="add_raw_' + random + '">\n' +
                    '                <div class="col-md-5">\n' +
                    '                    <div class="form-inline">\n' +
                    '                        <label>' + '{{ trans('smsprovider::smsgateway.parameter') }}' + '</label>\n' +
                    '                        <input type="text" name="api_add_name[]" class="form-control">\n' +
                    '                    </div>\n' +
                    '\n' +
                    '                </div>\n' +
                    '                <div class="col-md-5">\n' +
                    '                    <div class="form-inline">\n' +
                    '                        <label>' + '{{ trans('smsprovider::smsgateway.value') }}' + '</label>\n' +
                    '                        <input type="text" name="api_add_value[]" class="form-control">\n' +
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