@extends('smsprovider::layouts.master')

@section('content')

    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div class="col-md-12">
                <h1 class="pager text-primary"> {{ trans('smsprovider::smsgateway.edit_template_title') }} [<u
                            class="text-muted" style="display: inline-block;">{{ $template->message_type }}</u>]</h1>
                <hr>
            </div>
            <div class="col-md-10 col-md-offset-1">
                <form action="{{ route('smsprovider.providers.update_template') }}" method="post">
                    {!! csrf_field() !!}
                    <input name="template_id" value="{{ $template->id }}" type="hidden">

                    <div class="form-group">
                        <label for="api_company">{{ trans('smsprovider::smsgateway.title') }}</label>
                        <input type="text" name="title" value="{{ $template->message_type }}" id="title"
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="messag">{{ trans('smsprovider::smsgateway.message') }}</label>
                        <textarea type="text" name="message" id="message"
                                  class="form-control">{{ $template->message }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">{{ trans('smsprovider::smsgateway.status') }}</label>
                        <select name="status" id="status" class="form-control">
                            <option @if(!$template->status) selected @endif>{{ trans('smsprovider::smsgateway.frozen') }}</option>
                            <option @if($template->status) selected @endif>{{ trans('smsprovider::smsgateway.available') }}</option>
                        </select>
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

@stop