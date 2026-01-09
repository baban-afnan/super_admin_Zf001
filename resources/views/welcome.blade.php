<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arewa Smart - Admin Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-color: #ffffff;
            --text-secondary: #e0e0e0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('assets/img/bg/card-bg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #1a1a1a; /* Fallback */
            position: relative;
            overflow: hidden;
        }

        /* Dark overlay for the main background to make the card pop */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dim the background */
            z-index: 0;
        }
        
        .loader-container {
            width: 90%;
            max-width: 900px;
            min-height: 550px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .background-image-inner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/landing/api.png');
            background-size: cover;
            background-position: center;
            opacity: 0.6;
            z-index: 1;
            transition: transform 10s ease;
        }

        .loader-container:hover .background-image-inner {
            transform: scale(1.05);
        }
        
        .overlay-gradient {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(206, 89, 21, 0.9) 0%, rgba(209, 23, 23, 0.8) 100%);
            mix-blend-mode: multiply;
            z-index: 2;
        }
        
        .loader-content {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            text-align: center;
            color: var(--text-color);
        }
        
        .loader-title {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
            background: linear-gradient(to right, #ffffff, #ffd1a9);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: slideDown 0.8s ease-out forwards;
        }
        
        .encouragement-text {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            min-height: 3.6rem; /* Fixed height to prevent jumping */
            font-weight: 400;
            color: var(--text-secondary);
            max-width: 600px;
            line-height: 1.6;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        /* Modern Spinner */
        .loader-wrapper {
            position: relative;
            margin-bottom: 2.5rem;
        }

        .loader {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 30px rgba(255, 126, 95, 0.4);
        }

        .circular-progress {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: absolute;
            border: 3px solid transparent;
            border-top-color: #fff;
            animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }

        .circular-progress::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #ffd1a9;
            animation: spin 2s linear infinite reverse;
        }

        .percentage-display {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            z-index: 2;
        }
        
        .progress-bar-container {
            width: 100%;
            max-width: 320px;
            height: 6px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 100px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: #fff;
            border-radius: 100px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
            transition: width 0.3s ease-out;
        }
        
        /* Login Action */
        .login-container {
            margin-top: 2.5rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none; /* Disabled initially */
        }
        
        .login-container.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .login-btn {
            background: #fff;
            color: #d33f18; /* Brand color approximation */
            border: none;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            border-radius: 100px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .login-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }
        
        .login-btn:active {
            transform: translateY(-1px);
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-text-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .loader-title {
                font-size: 2.5rem;
            }
            
            .loader-container {
                width: 95%;
                min-height: 500px;
                margin: 1rem;
            }
            
            .encouragement-text {
                font-size: 1.1rem;
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .loader-title {
                font-size: 2rem;
            }
            
            .loader-container {
                min-height: 450px;
            }

            .loader {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="loader-container">
        <div class="background-image-inner"></div>
        <div class="overlay-gradient"></div>
        
        <div class="loader-content">
            <h1 class="loader-title">Arewa Smart</h1>
            
            <div class="encouragement-text" id="encouragement-text">
                Initializing safe environment...
            </div>
            
            <div class="loader-wrapper" id="loader-wrapper">
                <div class="loader">
                    <div class="circular-progress"></div>
                    <div class="percentage-display" id="percentage">0%</div>
                </div>
            </div>
            
            <div class="progress-bar-container" id="progress-container">
                <div class="progress-bar-fill" id="progress-bar"></div>
            </div>
            
            <div class="login-container" id="login-container">
                <p style="margin-bottom: 1rem; font-weight: 500; font-size: 1.1rem;">Setup Complete</p>
                <button class="login-btn" id="login-btn">
                    Access Dashboard <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        const statements = [
            "Initializing connection...",
            "Verifying security protocols...",
            "Great things take time...",
            "Optimizing your experience...",
            "Almost there, getting ready...",
            "Preparing your dashboard..."
        ];

        const els = {
            text: document.getElementById('encouragement-text'),
            percent: document.getElementById('percentage'),
            bar: document.getElementById('progress-bar'),
            loginContainer: document.getElementById('login-container'),
            loginBtn: document.getElementById('login-btn'),
            loaderWrapper: document.getElementById('loader-wrapper'),
            progressContainer: document.getElementById('progress-container')
        };

        let progress = 0;
        let statementIdx = 0;

        function updateText(msg) {
            els.text.style.opacity = 0;
            setTimeout(() => {
                els.text.textContent = msg || statements[statementIdx++ % statements.length];
                els.text.style.opacity = 1;
            }, 300);
        }

        function finishLoading() {
            // Smooth finish
            els.percent.textContent = "100%";
            els.bar.style.width = "100%";
            
            updateText("Welcome to Arewa Smart Staff Portal");
            
            setTimeout(() => {
                // Determine if we should hide the loader elements or just fade them
                els.loaderWrapper.style.transition = "opacity 0.5s ease";
                els.progressContainer.style.transition = "opacity 0.5s ease";
                els.loaderWrapper.style.opacity = 0;
                els.progressContainer.style.opacity = 0;
                
                // Show login
                setTimeout(() => {
                   els.loaderWrapper.style.display = 'none'; 
                   els.progressContainer.style.display = 'none'; 
                   els.loginContainer.classList.add('visible');
                }, 500);

            }, 800);
        }

        function simulate() {
            if (progress >= 100) {
                finishLoading();
                return;
            }

            // Variable speed: start slowish, accelerate in middle, slow at end
            let increment = Math.random() * 2 + 0.5;
            if (progress > 30 && progress < 80) increment = Math.random() * 4 + 1;
            
            progress = Math.min(progress + increment, 100);
            
            els.bar.style.width = `${progress}%`;
            els.percent.textContent = `${Math.floor(progress)}%`;

            // Update text rarely
            if (Math.floor(progress) % 20 === 0 && Math.random() > 0.3) {
                updateText();
            }

            if (progress < 100) {
                requestAnimationFrame(() => setTimeout(simulate, 50));
            } else {
                 finishLoading();
            }
        }

        // Redirect logic
        els.loginBtn.addEventListener('click', function() {
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Loading...';
            this.style.opacity = 0.8;
            this.disabled = true;

            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
            }, 800);
        });

        // Start
        setTimeout(() => {
            simulate();
            // Rotate text independently of progress for liveliness
            setInterval(() => updateText(), 3500);
        }, 500);

    </script>
</body>
</html>