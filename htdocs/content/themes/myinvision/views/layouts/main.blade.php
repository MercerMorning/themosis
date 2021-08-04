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
</body>
</html>
