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
    <!-- End css -->

    <!-- Page CSS -->
    <style>
        :root {
            --orange-primary: #ff6b35;
            --yellow-primary: #ffd23f;
            --green-primary: #27ae60;
            --coral-pink: #ff8a80;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
        }

        .error-card {
            border-radius: 30px;
            padding: 3rem 2.5rem;
            max-width: 800px;
            width: 100%;
            height: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 3px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: white;
            border-radius: 33px;
            z-index: -1;
        }

        .error-number {
            font-size: 8rem;
            font-weight: 900;
            color: #2c3e50;
            line-height: 0.8;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .error-subtitle {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        .btn-home {
            background: var(--green-primary);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-home:hover {
            background: #219a52;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .illustration {
            position: relative;
            margin-left: auto;
            width: 200px;
            height: 200px;
        }

        .character {
            width: 180px;
            height: 180px;
            position: relative;
            margin: auto;
        }

        .head {
            width: 80px;
            height: 80px;
            background: var(--coral-pink);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            position: relative;
            margin: 0 auto 10px;
            animation: float 3s ease-in-out infinite;
        }

        .hair {
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .hair-strand {
            width: 3px;
            height: 15px;
            background: #2c3e50;
            border-radius: 50px;
            display: inline-block;
            margin: 0 2px;
            animation: wiggle 2s ease-in-out infinite;
        }

        .hair-strand:nth-child(1) { animation-delay: 0s; }
        .hair-strand:nth-child(2) { animation-delay: 0.2s; }
        .hair-strand:nth-child(3) { animation-delay: 0.4s; }

        .face {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .eye {
            width: 8px;
            height: 8px;
            background: #2c3e50;
            border-radius: 50%;
            display: flex;
            margin: 0 8px;
            animation: blink 4s infinite;
        }

        .eye.left {
            transform: rotate(-15deg);
        }

        .eye.right {
            transform: rotate(30deg);
        }

        .mouth {
            width: 20px;
            height: 10px;
            border: 2px solid #2c3e50;
            border-top: none;
            border-radius: 0 0 20px 20px;
            margin: 10px auto 0;
        }

        .body {
            width: 90px;
            height: 60px;
            background: var(--yellow-primary);
            border-radius: 15px;
            margin: 0 auto;
            position: relative;
            animation: float 3s ease-in-out infinite;
            animation-delay: 0.5s;
        }

        .arm {
            width: 20px;
            height: 40px;
            background: var(--coral-pink);
            border-radius: 10px;
            position: absolute;
            top: 10px;
        }

        .arm.left {
            left: -15px;
            transform: rotate(-20deg);
            animation: scratch 2s ease-in-out infinite;
        }

        .arm.right {
            right: -15px;
            transform: rotate(20deg);
        }

        .hand {
            width: 15px;
            height: 15px;
            background: var(--coral-pink);
            border-radius: 50%;
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
        }

        .thinking-lines {
            position: absolute;
            top: -20px;
            right: -30px;
        }

        .line {
            width: 30px;
            height: 2px;
            background: #2c3e50;
            border-radius: 1px;
            margin: 5px 0;
            opacity: 0.6;
            animation: fade 2s ease-in-out infinite;
        }

        .line:nth-child(1) { animation-delay: 0s; width: 25px; }
        .line:nth-child(2) { animation-delay: 0.3s; width: 35px; }
        .line:nth-child(3) { animation-delay: 0.6s; width: 20px; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
        }

        @keyframes blink {
            0%, 90%, 100% { height: 8px; }
            95% { height: 2px; }
        }

        @keyframes scratch {
            0%, 100% { transform: rotate(-20deg); }
            50% { transform: rotate(-10deg); }
        }

        @keyframes fade {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        .decorative-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
            border-radius: 30px;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
        }

        .shape-1 {
            width: 60px;
            height: 60px;
            background: var(--orange-primary);
            border-radius: 50%;
            top: 10%;
            right: 15%;
            animation: float 4s ease-in-out infinite;
        }

        .shape-2 {
            width: 40px;
            height: 40px;
            background: var(--green-primary);
            transform: rotate(45deg);
            bottom: 15%;
            left: 10%;
            animation: float 3s ease-in-out infinite reverse;
        }

        .shape-3 {
            width: 50px;
            height: 25px;
            background: var(--yellow-primary);
            border-radius: 25px;
            top: 60%;
            right: 5%;
            animation: float 5s ease-in-out infinite;
        }

        @media (max-width: 768px) {
            .error-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .error-number {
                font-size: 6rem;
            }
            
            .error-title, .error-subtitle {
                font-size: 1.8rem;
            }
            
            .row {
                flex-direction: column-reverse;
            }
            
            .illustration {
                margin: 2rem auto;
                width: 150px;
                height: 150px;
            }
            
            .character {
                width: 140px;
                height: 140px;
            }
        }

        @media (max-width: 480px) {
            .error-number {
                font-size: 4.5rem;
            }
            
            .error-title, .error-subtitle {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <!-- Decorative Shapes -->
        <div class="decorative-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6 text-center text-md-start">
                <div class="error-number">404</div>
                <h1 class="error-title">Something went</h1>
                <h1 class="error-subtitle">WRONG!</h1>
                <button class="btn btn-home mt-3">
                    <i class="fas fa-home me-2"></i>Back to Homepage
                </button>
            </div>
            
            <div class="col-lg-5 col-md-6">
                
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Button click animation
            const backButton = document.querySelector('.btn-home');
            
            backButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Add click effect
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-2px) scale(1)';
                }, 150);
                
                // Simulate navigation (you can replace with actual navigation)
                setTimeout(() => {
                    alert('Redirecting to homepage...');
                    // window.location.href = '/';
                }, 300);
            });

            // Random floating animation for decorative shapes
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                setInterval(() => {
                    const randomX = Math.random() * 20 - 10;
                    const randomY = Math.random() * 20 - 10;
                    shape.style.transform += ` translate(${randomX}px, ${randomY}px)`;
                }, 3000 + (index * 1000));
            });

            // Add subtle parallax effect on mouse move
            document.addEventListener('mousemove', function(e) {
                const card = document.querySelector('.error-card');
                const { clientX, clientY } = e;
                const { innerWidth, innerHeight } = window;
                
                const xPercent = (clientX / innerWidth - 0.5) * 2;
                const yPercent = (clientY / innerHeight - 0.5) * 2;
                
                card.style.transform = `
                    perspective(1000px) 
                    rotateY(${xPercent * 2}deg) 
                    rotateX(${yPercent * -2}deg)
                `;
            });

            // Reset transform when mouse leaves
            document.addEventListener('mouseleave', function() {
                const card = document.querySelector('.error-card');
                card.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
            });
        });
    </script>
</body>
</html>