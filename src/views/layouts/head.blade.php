<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="{{  url('packages\mody\smsgateway\bootstrap.min.css') }}" rel="stylesheet">

    @if(App::isLocale('ar'))
        <link href="{{ url('packages\mody\smsgateway\bootstrap.rtl.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{  url('packages\mody\smsgateway\style.css') }}" rel="stylesheet">
    @yield('styles')
</head>