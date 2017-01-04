<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.1.5/quill.snow.css" rel="stylesheet">
    <link href="/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/css/video-js.css" rel="stylesheet" type="text/css">
    <link href="/css/admin.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
@yield('content')

        <!-- Scripts -->
<script src="https://code.jquery.com/jquery-2.1.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>
<script type="text/javascript" src="/js/imagesloaded.pkgd.min.js"></script>
<script src="/js/packery.pkgd.min.js"></script>
<script type="text/javascript" src="/js/masonry.pkgd.js"></script>
<script type="text/javascript" src="/js/video.js"></script>
<script type="text/javascript" src="/js/addy.js"></script>
</body>
</html>
