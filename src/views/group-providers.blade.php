@extends('smsprovider::layouts.master')

@section('content')


    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div class="col-md-12">
                <h1 class="pager text-primary">{{ $title ?? '' }}</h1>
                <hr>
            </div>
            <div class="col-md-10 col-md-offset-1">

                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="info">
                        <th>#</th>
                        <th>{{ trans('smsprovider::smsgateway.api_company') }}</th>
                        <th>{{ trans('smsprovider::smsgateway.api_url') }}</th>
                        <th>{{ trans('smsprovider::smsgateway.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody id="table-body">
                    @foreach($tryone->chunk(15) as $item_ch)
                        @foreach($item_ch as $item)
                            <tr id="{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->company_name }}</td>
                                <td>{{ $item->api_url }}</td>
                                <td>

                                    <div class="btn-group">

                                        @if($item->trashed())
                                            <button class="btn btn-warning" onclick="recover()" type="button">
                                                {{ trans('smsprovider::smsgateway.recover') }}
                                            </button>
                                        @else
                                            @if($item->isDefault())
                                                <button class="btn btn-primary"
                                                        style="background: #d5da71;border: #d5da71; color:#0b031d;"
                                                        onclick="removeDefault()" type="button">
                                                    {{ trans('smsprovider::smsgateway.removeDefault') }}
                                                </button>
                                            @else
                                                <button class="btn btn-primary" onclick="setDefault()" type="button">
                                                    {{ trans('smsprovider::smsgateway.setDefault') }}
                                                </button>
                                            @endif
                                            <a class="btn btn-info"
                                               href="{{ route('smsprovider.providers.edit_provider', $item->id) }}">
                                                {{ trans('smsprovider::smsgateway.edit') }}
                                            </a>

                                            <button class="btn btn-warning" onclick="softDelete()" type="button">
                                                {{ trans('smsprovider::smsgateway.trash') }}
                                            </button>

                                            <button class="btn btn-default" style="background: #da0000;color:#eee;" onclick="destroy()" type="button">
                                                {{ trans('smsprovider::smsgateway.delete') }}
                                            </button>
                                        @endif
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    </tbody>
                </table>

                <div class="text-center">
                    {!! $tryone->links() !!}
                </div>
            </div>

        </div>
    </div>

@stop

@section('styles')

@stop
@section('scripts')
    <script>
        $(document).ready(function () {


        });
    </script>
@stop