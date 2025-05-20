<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bg-color: #f4f6f9;
      --card-bg: #fff;
      --text-color: #333;
    }

    body.dark-mode {
      --bg-color: #1e1e2f;
      --card-bg: #2a2a3d;
      --text-color: #f1f1f1;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--bg-color);
      transition: background 0.3s, color 0.3s;
    }

    .login-card {
      background-color: var(--card-bg);
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-card h2 {
      margin-bottom: 20px;
      color: var(--text-color);
    }

    .login-card input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    .login-card button {
      width: 100%;
      padding: 12px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    .toggle-password {
      position: absolute;
      right: 20px;
      top: 38px;
      cursor: pointer;
      color: #999;
    }

    .input-wrapper {
      position: relative;
    }

    .dark-toggle {
      margin-top: 20px;
      font-size: 14px;
      color: var(--text-color);
      cursor: pointer;
      text-decoration: underline;
    }

    .login-card p {
      font-size: 14px;
      color: #888;
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <h2><i class="fas fa-lock"></i> Admin Login</h2>
    <form onsubmit="return login(event)">
      <input type="email" id="email" placeholder="Email" required />
      <div class="input-wrapper">
        <input type="password" id="password" placeholder="Password" required />
        <i class="fas fa-eye toggle-password" onclick="togglePassword(this)"></i>
      </div>
      <button type="submit">Login</button>
    </form>
    <div class="dark-toggle" onclick="toggleMode()"> Dark Mode</div>
   <br>
  </div>

  <script>
    function togglePassword(icon) {
      const password = document.getElementById("password");
      if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }

    function toggleMode() {
      document.body.classList.toggle("dark-mode");
    }

    function login(event) {
      event.preventDefault();
      const email = document.getElementById("email").value;
      const pass = document.getElementById("password").value;

      if (email && pass) {
        
        // Redirect to dashboard page
        window.location.href = "/";
      } else {
        alert("Please fill out all fields.");
      }
    }
  </script>
<script>
    // Apply stored mode on page load
    if (localStorage.getItem("mode") === "dark") {
      document.body.classList.add("dark-mode");
    }
  
    // Toggle dark mode and store preference
    function toggleMode() {
      document.body.classList.toggle("dark-mode");
      const mode = document.body.classList.contains("dark-mode") ? "dark" : "light";
      localStorage.setItem("mode", mode);
    }
  </script>
  
</body>
</html>
