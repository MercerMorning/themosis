<!DOCTYPE html>
<html {!! get_language_attributes() !!}>
<head>
    <meta charset="{{ get_bloginfo('charset') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    @head
</head>
<body @php(body_class())>
@yield('main_content')
@footer
{{--<script>--}}
{{--    const socket = new WebSocket("ws://localhost:8999");--}}

{{--    socket.onopen = () => {--}}
{{--        socket.send("Hello!");--}}
{{--    };--}}

{{--    socket.onmessage = (data) => {--}}
{{--        console.log(data.data);--}}
{{--    };--}}
{{--</script>--}}
</body>
</html>
