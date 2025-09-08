<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Meta Starts --}}
    @include('layouts.administration.partials.metas')
    {{-- Meta Ends --}}
    <title>{{ config('app.name') }} || Not Found</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset(config('app.favicon')) }}" />

    <!-- Start css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100..900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js">
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" />
    <!-- End css -->
    
    <!-- Page CSS -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Public Sans", sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container--0- {
            width: 100%;
            max-width: 1200px;
            background-color: #ffffff;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        /* Flex container for gif + content */
        .container-1-2-0 {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        /* GIF styling */
        .container-1-2-0 img {
            max-width: 400px;
            width: 100%;
            height: auto;
        }

        /* Content block */
        .container-1-2-1 {
            flex: 1;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .container-2-3-0 {
            font-family: 'Inter', sans-serif;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .text-404 {
            
            font-size: 300px;
            font-weight: bold;
            color: #7367F0;
            background: linear-gradient(118.28deg, #7367F0 27.35%, #9D94F4 85.96%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
        }

        .text-3-4-1 {
            margin-top: -100px;
            color: #000000;
            font-size: 64px;
            font-weight: 500;
            text-align: center;
        }

        .container-2-3-1 {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 6px;
        }

        .text-3-4-0 {
            color: #5d596c;
            font-size: 21px;
            font-weight: 500;
            text-align: center;
            line-height: 1.6;
        }

        .container-3-4-1 {
            background: linear-gradient(118.28deg, #7367F0 27.35%, #9D94F4 85.96%);
            border-radius: 6px;
            padding: 14px 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(115, 103, 240, 0.2);
            text-decoration: none;
        }

        .container-3-4-1:hover {
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(115, 103, 240, 0.3);
        }

        .text-4-5-0 {
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .text-3-4-1 {
                font-size: 48px;
            }
            .text-3-4-0 {
                font-size: 18px;
            }
        }

        @media (max-width: 600px) {
            .text-3-4-1 {
                font-size: 36px;
            }
            .text-3-4-0 {
                font-size: 16px;
            }
            .container-1-2-1 {
                gap: 20px;
            }
            svg {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container--0-">
        <div class="container-1-2-0">
            <!-- GIF -->
            <img
                src="{{ asset($image) }}"
                alt="Error {{ $statusCode }}"
            />

            <!-- Content -->
            <div class="container-1-2-1">
                <div class="container-2-3-0">
                    <div class="text-404">{{ $statusCode }} </div>
                    <div class="text-3-4-1">{{ $title }}</div>
                    
                    {{-- @auth
                        <p class="mb-4">{{ $exception->getMessage() }}</p>
                    @endauth
                    @auth
                        <p class="mt-3 text-muted">Debug: {{ $exception->getMessage() }}</p>
                    @endauth --}}
                </div>
                
                <div class="container-2-3-1">
                    <div class="text-3-4-0">
                        {{ $message }}
                    </div>
                    <a href="{{ url()->previous() }}" class="container-3-4-1 mt-3">
                        <div class="text-4-5-0"><i class="ti ti-arrow-left"></i> Back To Previous</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
