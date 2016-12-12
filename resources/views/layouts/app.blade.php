<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fabiana Formica</title>

    <!-- Styles -->
    <link href="/css/video-js.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.quilljs.com/1.1.5/quill.snow.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/imagesloaded.pkgd.min.js"></script>
    <script type="text/javascript" src="/js/masonry.pkgd.js"></script>
    <script type="text/javascript" src="/js/video.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>
