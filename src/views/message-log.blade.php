@extends('smsprovider::layouts.master')

@section('content')

    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div id="message-info"></div>
            <div class="col-md-12">
                <h1 class="pager text-primary">{{ trans('smsprovider::smsgateway.log_title') }}</h1>
                <hr>
            </div>
            @if($messages->count() > 0)
                <div class="col-md-10 col-md-offset-1">

                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('smsprovider::smsgateway.date') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.message') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.mobile') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.details') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.api_company') }}</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach($messages->chunk(20) as $item_ch)
                            @foreach($item_ch as $item)
                                <tr id="itemrow-{{ $item->id }}" @if($item->status) class="success"
                                    @else class="danger" @endif>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->message }}</td>
                                    <td>{{ $item->client_number }}</td>
                                    <td class="text-info">{{ $item->status ? trans('smsprovider::smsgateway.sent_success') : trans('smsprovider::smsgateway.sent_fail') }}</td>
                                    <td>{{ $item->provider->company_name }}</td>

                                </tr>
                            @endforeach
                        @endforeach

                        </tbody>
                    </table>

                    <div class="text-center">
                        {!! $messages->links() !!}
                    </div>
                </div>
            @else
                <h3 class="text-center text-muted">{{ trans('smsprovider::smsgateway.empty_info') }}</h3>
            @endif
        </div>
    </div>

@stop

@section('styles')

@stop

@section('scripts')

@stop