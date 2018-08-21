<!DOCTYPE html>
<html lang="en">
@include('smsprovider::layouts.head')

<body>

<div class="se-pre-con"></div>

<div id="left_content">

    @include('smsprovider::layouts.messages')

    @yield('content')
</div>
@include('smsprovider::layouts.footer')

</body>
</html>
