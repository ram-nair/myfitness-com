<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="{{asset('css/errorStyle.css')}}" />

        <!--[if lt IE 9]>
                  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
                <![endif]-->
    </head>
    <body>

        <div id="notfound">
            <div class="notfound">
                <div class="notfound-404"></div>
                <h1>@yield('code')</h1>
                <h2>@yield('short_message')</h2>
                <p>@yield('message')</p>
                <a href="{{url('/')}}">Back to homepage</a>
            </div>
        </div>

    </body>
</html>
