@if($errors->any())
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <!-- if there are creation errors, they will show here -->
            <ul class="alert alert-danger text-center list-unstyled">
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if(session()->has('success'))
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <ul class='h4 alert alert-success text-center list-unstyled'>
                {!! session()->get('success') !!}
            </ul>
        </div>
    </div>
@endif

@if(session()->has('error'))
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <ul class='h4 alert alert-danger text-center list-unstyled'>
                {!! session()->get('error') !!}
            </ul>
        </div>
    </div>
@endif


@if(session()->has('warning'))
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <ul class='h4 alert alert-warning text-center list-unstyled'>
                {!! session()->get('warning') !!}
            </ul>
        </div>
    </div>
@endif


@if(session()->has('message'))
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <ul class='h4 alert alert-info text-center list-unstyled'>
                {!! session()->get('message') !!}
            </ul>
        </div>
    </div>
@endif