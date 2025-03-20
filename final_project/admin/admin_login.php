<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        h2{
            color: #4A5BE6;
        }
        .login-card {
        width: 300px;
        height:330px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: perspective(1000px) rotateX(5deg);
            transition: transform 0.2s;
        }
        .login-card:hover {
            transform: perspective(1000px) rotateX(10deg);
        }
        .login-card h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-card label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 94%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #E5E5E5;
            border-radius: 5px;
        }
        .login-card input[type="checkbox"] {
            margin-right: 10px;
        }
        .login-card .btn {
            margin-top:20px;
            background-color: #4A5BE6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .login-card .btn:hover {
            background-color:rgb(32, 54, 224);
        }
        .login-card .btn:active {
            background-color:rgb(53, 29, 156);
        }
        .error {
            margin-top:20px;
            color: red;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Login</h2>
        <form id="loginForm">
            <label for="username">Username</label>
            <input type="text" id="username"  >

            <label for="password">Password</label>
            <input type="password" id="password" >

            <label for="showPassword">
                <input type="checkbox" id="showPassword"> Show Password
            </label>

            <button type="submit" class="btn">Sign In</button>
            <div class="error" id="error-message"></div>
        </form>
    </div>

    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');

            // Clear any previous error message
            errorMessage.textContent = '';

            // Check if username and password are correct
            if (username === 'admin' && password === 'admin') {
                // Redirect to the next page (change 'nextPage.html' to the desired URL)
                window.location.href = 'admin_home.php'; // Replace with your next page URL
            } else {
                // Show error message if credentials are incorrect
                errorMessage.textContent = 'Invalid username or password.';
            }
        });
    </script>
</body>
</html>