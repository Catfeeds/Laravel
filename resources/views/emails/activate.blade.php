<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yogooooo</title>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: Avenir, Helvetica, sans-serif;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 24px;
        }

         a {
            color: #29b2fe;
            padding: 0 25px;
            font-size: 12px;
            letter-spacing: .1rem;
        }

         .text {
             color: #74787E;
             font-size: 16px;
             line-height: 1.5em;
             margin-top: 0;
         }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            Yogooooo
        </div>

        <p class="text">Hello! Welcome to join Yogooooo! Please click the link below to activate your mail. Thank you!</p>

        <a href="{{ config('url.activateEmail') . "?token=$token"  }}" target="_blank">{{ config('url.activateEmail') . "?token=$token"  }}</a>
    </div>
</div>
</body>
</html>
