<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} || Maintenance Mode</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* no scrollbars */
        }

        .lottie-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff; /* optional background color */
        }

        .lottie-wrapper lottie-player {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="lottie-wrapper">
        <lottie-player
            src="{{ asset($image) }}"
            background="transparent"
            speed="1"
            autoplay
        ></lottie-player>
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>
</html>
