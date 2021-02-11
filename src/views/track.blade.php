@extends('smsprovider::layouts.master')

@section('content')

    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div id="message-info"></div>
            <div class="col-md-12">
                <h1 class="pager text-primary">{{ trans('smsprovider::smsgateway.track_title') }}</h1>
                <hr>
            </div>
            @if($track->count() > 0)
                <div class="col-md-10 col-md-offset-1">

                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="info">
                            <th>#</th>
                            <th>{{ trans('smsprovider::smsgateway.date') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.details') }}</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach($track as $item)
                                <tr id="itemrow-{{ $item->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-info">{{ $item->description }}</td>
                                </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <div class="text-center">
                        {!! $track->links() !!}
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