@extends('smsprovider::layouts.master')

@section('content')


    <div class="container" style="margin-top:3em;">
        <div class="row">

            <div id="message-info"></div>
            <div class="col-md-12">
                <h1 class="pager text-primary">{{ $title ?? '' }}</h1>
                <hr>
            </div>
            @if($templates->count() > 0)
                <div class="col-md-10 col-md-offset-1">

                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr class="info">
                            <th>#</th>
                            <th>{{ trans('smsprovider::smsgateway.title') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.message') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.status') }}</th>
                            <th>{{ trans('smsprovider::smsgateway.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody id="table-body">
                        @foreach($templates->chunk(20) as $item_ch)
                            @foreach($item_ch as $item)
                                <tr id="itemrow-{{ $item->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->message_type }}</td>
                                    <td>{{ $item->message }}</td>
                                    <td id="s_text-{{ $item->id }}">{{ $item->status ? trans('smsprovider::smsgateway.available') : trans('smsprovider::smsgateway.frozen') }}</td>
                                    <td>
                                        <div class="loader" style="display: none;" id="load-{{ $item->id }}"></div>
                                        <div class="btn-group">

                                            @if($item->trashed())
                                                <button class="btn btn-warning" onclick="recover({{ $item->id }})"
                                                        type="button">
                                                    {{ trans('smsprovider::smsgateway.recover') }}
                                                </button>
                                            @else
                                                @if($item->status)
                                                    <button class="btn btn-primary"
                                                            style="background: #d5da71;border: #d5da71; color:#0b031d;"
                                                            data-id="{{ $item->id }}" type="button"
                                                            id="status-{{ $item->id }}" onclick="changeStatus({{ $item->id }})">
                                                        {{ trans('smsprovider::smsgateway.freeze') }}
                                                    </button>
                                                @else
                                                    <button class="btn btn-primary" id="status-{{ $item->id }}"
                                                            data-id="{{ $item->id }}" type="button"  onclick="changeStatus({{ $item->id }})">
                                                        {{ trans('smsprovider::smsgateway.enable') }}
                                                    </button>
                                                @endif
                                                <a class="btn btn-info"
                                                   href="{{ route('smsprovider.providers.edit_template', $item->id) }}">
                                                    {{ trans('smsprovider::smsgateway.edit') }}
                                                </a>

                                                <button class="btn btn-warning" onclick="softDelete({{ $item->id }})"
                                                        type="button">
                                                    {{ trans('smsprovider::smsgateway.trash') }}
                                                </button>

                                                <button class="btn btn-default" style="background: #da0000;color:#eee;"
                                                        onclick="destroy({{ $item->id }})" type="button">
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
                        {!! $templates->links() !!}
                    </div>
                </div>
            @else
                <h3 class="text-center text-muted">{{ trans('smsprovider::smsgateway.empty_info') }}</h3>
            @endif
        </div>
    </div>


    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <div class="col-md-2">
                    <span class="close_modal">&times;</span>
                </div>
                <div class="col-md-10">
                    <div id="modal_content"></div>

                    <div id="btn_do">

                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
        </div>

    </div>

@stop

@section('styles')
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 30px;
            height: 30px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* The Modal (background) */
        .modal {
            /*display: none; !* Hidden by default *!*/
            position: fixed; /* Stay in place */
            z-index: 999999; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        .close_modal {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close_modal:hover,
        .close_modal:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@stop

@section('scripts')

    <script src="{{ get_url('packages\mody\smsprovider\axios.min.js') }}"></script>
    <script>

        $(document).on('click', '.close_modal', function () {
            $('#myModal').modal('toggle');
        });

        function recover(id) {
            $('#myModal').modal('toggle');
            $('#modal_content').empty().append('<h3>' + '{{ trans('smsprovider::smsgateway.confirm_template_recover') }}' + '</h3>');
            $('#btn_do').empty().append('<button class="btn btn-success text-center"\n' +
                '                            id="modal_btn" onclick="doRecover(' + id + ')">' +
                '    {{ trans('smsprovider::smsgateway.confirm') }}' +
                '</button>')
        }

        function doRecover(id) {

            $('#myModal').modal('toggle');

            $('#load-' + id).css('display', 'block');

            axios.post('{{ route('smsprovider.providers.ajax.restore-template') }}', {
                template_id: id,
            })
                .then(function (response) {
                    if (response.data === true) {
                        $('#itemrow-' + id).fadeOut('slow').remove();
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-success">' + '{{ trans('smsprovider::smsgateway.recover_t-success') }}' + '</p>'
                        );
                    } else {
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.recover_t-error') }}' + '</p>'
                        );
                    }

                    $('#load-' + id).css('display', 'none');
                    setTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                })
                .catch(function () {
                    $('#load-' + id).css('display', 'none');
                    $('#message-info').empty().append(
                        '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.recover_t-error') }}' + '</p>'
                    );
                    etTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                });
        }

        function softDelete(id) {
            $('#myModal').modal('toggle');
            $('#modal_content').empty().append('<h3>' + '{{ trans('smsprovider::smsgateway.confirm_template_soft_delete') }}' + '</h3>');
            $('#btn_do').empty().append('<button class="btn btn-success text-center"\n' +
                '                            id="modal_btn" onclick="doSoftDelete(' + id + ')">' +
                '    {{ trans('smsprovider::smsgateway.confirm') }}' +
                '</button>')
        }

        function doSoftDelete(id) {

            $('#myModal').modal('toggle');

            $('#load-' + id).css('display', 'block');
            axios.post('{{ route('smsprovider.providers.ajax.trash-template') }}', {
                template_id: id,
            })
                .then(function (response) {
                    if (response.data === true) {
                        $('#itemrow-' + id).fadeOut('slow').remove();
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-success">' + '{{ trans('smsprovider::smsgateway.delete_t-success') }}' + '</p>'
                        );
                    } else {
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.delete_t-error') }}' + '</p>'
                        );
                    }

                    $('#load-' + id).css('display', 'none');
                    setTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                })
                .catch(function () {

                    $('#load-' + id).css('display', 'none');
                    $('#message-info').empty().append(
                        '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.delete_t-error') }}' + '</p>'
                    );
                    etTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                });
        }

        function destroy(id) {
            $('#myModal').modal('toggle');
            $('#modal_content').empty().append('<h3>' + '{{ trans('smsprovider::smsgateway.confirm_template_destroy') }}' + '</h3>');
            $('#btn_do').empty().append('<button class="btn btn-success text-center"\n' +
                '                            id="modal_btn" onclick="doDestroy(' + id + ')">' +
                '    {{ trans('smsprovider::smsgateway.confirm') }}' +
                '</button>')
        }

        function doDestroy(id) {
            $('#myModal').modal('toggle');

            $('#load-' + id).css('display', 'block');
            axios.post('{{ route('smsprovider.providers.ajax.destroy-template') }}', {
                template_id: id,
            })
                .then(function (response) {
                    $('#load-' + id).css('display', 'none');
                    if (response.data === true) {
                        $('#itemrow-' + id).fadeOut('slow').remove();
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-success">' + '{{ trans('smsprovider::smsgateway.destroy_t-success') }}' + '</p>'
                        );
                    } else {
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.destroy_t-error') }}' + '</p>'
                        );
                    }

                    $('#load-' + id).css('display', 'none');
                    setTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                })
                .catch(function () {
                    $('#load-' + id).css('display', 'none');
                    $('#message-info').empty().append(
                        '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.destroy_t-error') }}' + '</p>'
                    );
                    etTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                });
        }

        function changeStatus(id) {
            $('#myModal').modal('toggle');
            $('#modal_content').empty().append('<h3>' + '{{ trans('smsprovider::smsgateway.confirm_template_change_status') }}' + '</h3>');
            $('#btn_do').empty().append('<button class="btn btn-success text-center"\n' +
                '                            id="modal_btn" onclick="doChangeStatus(' + id + ')">' +
                '    {{ trans('smsprovider::smsgateway.confirm') }}' +
                '</button>')
        }

        function doChangeStatus(id) {
            $('#myModal').modal('toggle');

            $('#load-' + id).css('display', 'block');
            axios.post('{{ route('smsprovider.providers.ajax.status-template') }}', {
                template_id: id,
            })
                .then(function (response) {
                    console.log(response);
                    $('#load-' + id).css('display', 'none');
                    if (response.data.res === true) {
                        // $('#itemrow-' + id).fadeOut('slow').remove();
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-success">' + '{{ trans('smsprovider::smsgateway.chng_status_success') }}' + '</p>'
                        );
                        if(response.data.stat == 0){
                            $('#status-'+id).replaceWith(
                                '<button class="btn btn-primary" style="background: #d5da71;border: #d5da71; color:#0b031d;"' +
                                ' data-id="'+id+'" type="button" id="status-'+id+'" onclick="changeStatus('+id+')">\n' +
                                '{{ trans('smsprovider::smsgateway.freeze') }}'+
                                '</button>');
                            $('#s_text-'+id).empty().append('{{ trans('smsprovider::smsgateway.available') }}');
                        }else{
                            $('#status-'+id).replaceWith('<button class="btn btn-primary"' +
                                ' data-id="'+id+'" type="button" id="status-'+id+'" onclick="changeStatus('+id+')">\n' +
                                '{{ trans('smsprovider::smsgateway.enable') }}'+
                                '</button>');
                            $('#s_text-'+id).empty().append('{{ trans('smsprovider::smsgateway.frozen') }}');
                        }

                    } else {
                        $('#message-info').empty().append(
                            '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.chng_status_error') }}' + '</p>'
                        );
                    }

                    $('#load-' + id).css('display', 'none');
                    setTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                })
                .catch(function () {
                    $('#load-' + id).css('display', 'none');
                    $('#message-info').empty().append(
                        '<p class="text-center alert alert-danger">' + '{{ trans('smsprovider::smsgateway.chng_status_error') }}' + '</p>'
                    );
                    etTimeout(function () {
                        $('#message-info').empty();
                    }, 2500);
                });
        }

    </script>
@stop