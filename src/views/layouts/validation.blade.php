
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <!-- if there are creation errors, they will show here -->
            <ul class="alert alert-danger text-center list-unstyled">
                @foreach($errors as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    </div>
