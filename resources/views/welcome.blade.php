<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arewa Smart - staff Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('assets/img/bg/card-bg.png');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }
        
        .loader-container {
            width: 100%;
            max-width: 800px;
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(238, 236, 236, 0.5);
            position: relative;
        }
        
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/img/landing/user2.jpg');
            background-size: cover;
            background-position: center;
            filter: brightness(0.7);
            z-index: 1;
        }
        
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(202, 93, 21, 0.85) 0%, rgba(235, 19, 19, 0.75) 100%);
            z-index: 2;
        }
        
        .loader-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            z-index: 3;
            color: white;
            text-align: center;
        }
        
        .loader-title {
            font-size: 2.8rem;
            margin-bottom: 20px;
            font-weight: 700;
            background: linear-gradient(to right, #ece5e3ff, #feb47b);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .encouragement-text {
            font-size: 1.5rem;
            margin-bottom: 40px;
            min-height: 80px;
            font-weight: 500;
            transition: opacity 0.8s ease;
            padding: 0 20px;
        }
        
        .loader {
            width: 120px;
            height: 120px;
            position: relative;
            margin-bottom: 40px;
        }
        
        .spinner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 8px solid rgba(255, 255, 255, 0.1);
            border-top: 8px solid #ff7e5f;
            animation: spin 1.5s linear infinite;
        }
        
        .inner-spinner {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 6px solid rgba(255, 255, 255, 0.05);
            border-top: 6px solid #feb47b;
            animation: spin 1s linear infinite reverse;
        }
        
        .progress-container {
            width: 80%;
            max-width: 400px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        
        .progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .percentage {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ff7e5f;
        }
        
        .login-redirect {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
            text-align: center;
            margin-top: 20px;
        }
        
        .login-btn {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(255, 126, 95, 0.3);
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(255, 126, 95, 0.4);
        }
        
        .login-btn i {
            margin-left: 10px;
        }
        
        .login-message {
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .loader-container {
                height: 450px;
                margin: 20px;
            }
            
            .loader-title {
                font-size: 2.2rem;
            }
            
            .encouragement-text {
                font-size: 1.3rem;
                min-height: 70px;
            }
        }
        
        @media (max-width: 480px) {
            .loader-container {
                height: 400px;
            }
            
            .loader-title {
                font-size: 1.8rem;
            }
            
            .encouragement-text {
                font-size: 1.1rem;
                min-height: 60px;
            }
            
            .loader {
                width: 100px;
                height: 100px;
            }
            
            .inner-spinner {
                width: 70px;
                height: 70px;
                top: 15px;
                left: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="loader-container">
        <div class="background-image"></div>
        <div class="overlay"></div>
        
        <div class="loader-content">
            <h1 class="loader-title">Welcome to Arewa Smart Idea</h1>
            
            <div class="encouragement-text" id="encouragement-text">
                Great things take time, and you're doing amazing!
            </div>
            
            <div class="loader">
                <div class="spinner"></div>
                <div class="inner-spinner"></div>
            </div>
            
            <div class="percentage" id="percentage">0%</div>
            
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            
            <div class="login-redirect" id="login-redirect">
                <div class="login-message">Ready to begin your journey?</div>
                <button class="login-btn" id="login-btn">
                    Go to Login <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Encouragement statements array
        const encouragementStatements = [
            "Great things take time, and you're doing amazing!",
            "Every step forward is progress, no matter how small.",
            "Your journey is unique and worthwhile.",
            "Stay positive, wonderful things are coming your way.",
            "Believe in yourself and all that you are.",
            "You're stronger than you think and more capable than you imagine.",
            "The only way to do great work is to love what you're doing.",
            "Your potential is endless. Keep going!",
            "Success is not final, failure is not fatal: it is the courage to continue that counts.",
            "You are capable of amazing things. Just a little more!"
        ];
        
        // DOM elements
        const encouragementText = document.getElementById('encouragement-text');
        const percentageElement = document.getElementById('percentage');
        const progressBar = document.getElementById('progress-bar');
        const loginRedirect = document.getElementById('login-redirect');
        const loginBtn = document.getElementById('login-btn');
        
        let progress = 0;
        let currentStatementIndex = 0;
        
        // Function to update encouragement text
        function updateEncouragementText() {
            encouragementText.style.opacity = 0;
            
            setTimeout(() => {
                encouragementText.textContent = encouragementStatements[currentStatementIndex];
                encouragementText.style.opacity = 1;
                
                currentStatementIndex++;
                if (currentStatementIndex >= encouragementStatements.length) {
                    currentStatementIndex = 0;
                }
            }, 300);
        }
        
        // Function to simulate loading progress
        function simulateLoading() {
            if (progress < 100) {
                // Increase progress by random amount between 1 and 5
                const increment = Math.floor(Math.random() * 5) + 1;
                progress = Math.min(progress + increment, 100);
                
                // Update progress bar and percentage
                progressBar.style.width = `${progress}%`;
                percentageElement.textContent = `${progress}%`;
                
                // Change encouragement text at certain intervals
                if (progress % 25 === 0 || progress === 10) {
                    updateEncouragementText();
                }
                
                // Schedule next update with random delay for realistic feel
                const delay = Math.floor(Math.random() * 200) + 100;
                setTimeout(simulateLoading, delay);
            } else {
                // Loading complete
                setTimeout(() => {
                    encouragementText.textContent = "All set! You're ready to begin.";
                    encouragementText.classList.add('fade-in');
                    
                    // Show login redirect
                    setTimeout(() => {
                        loginRedirect.style.opacity = "1";
                        loginRedirect.style.transform = "translateY(0)";
                    }, 500);
                }, 500);
            }
        }
        
        // Function to redirect to login route
        function redirectToLogin() {
            // In a real application, this would redirect to your login route
            // For this demo, we'll simulate a redirect with a message
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting...';
            loginBtn.disabled = true;
            
            // Simulate API call/redirect delay
            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
                
                // Reset button after alert
                loginBtn.innerHTML = 'Go to Login <i class="fas fa-arrow-right"></i>';
                loginBtn.disabled = false;
                
                // Here's what you would use in a real app:
                // window.location.href = "/login";
            }, 1000);
        }
        
        // Start the loading simulation
        setTimeout(simulateLoading, 1000);
        
        // Change encouragement text every 4 seconds during loading
        const encouragementInterval = setInterval(updateEncouragementText, 4000);
        
        // Add event listener to login button
        loginBtn.addEventListener('click', redirectToLogin);
        
        // Clear interval when loading is complete
        setTimeout(() => {
            clearInterval(encouragementInterval);
        }, 15000); // Clear after 15 seconds (loading should be done before this)
    </script>
</body>
</html>