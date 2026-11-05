<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      padding: 1.5rem;
    }

    .card {
      width: 100%;
      max-width: 420px;
      background: #ffffff;
      border-radius: 1rem;
      padding: 2.5rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    }

    .header {
      margin-bottom: 2rem;
      text-align: center;
    }

    .header h1 {
      font-size: 1.75rem;
      color: #0f172a;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .header p {
      color: #64748b;
      font-size: 0.95rem;
    }

    .form-group {
      margin-bottom: 1.25rem;
    }

    label {
      display: block;
      font-size: 0.875rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 0.5rem;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #cbd5e1;
      border-radius: 0.5rem;
      font-size: 0.95rem;
      color: #0f172a;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #475569;
      cursor: pointer;
    }

    .remember-me input {
      accent-color: #2563eb;
    }

    .forgot-password {
      color: #2563eb;
      text-decoration: none;
      font-weight: 500;
    }

    .forgot-password:hover {
      text-decoration: underline;
    }

    .btn-submit {
      width: 100%;
      padding: 0.75rem;
      background-color: #2563eb;
      color: #ffffff;
      border: none;
      border-radius: 0.5rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-submit:hover {
      background-color: #1d4ed8;
    }

    .btn-submit:active {
      transform: scale(0.99);
    }

    .footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.875rem;
      color: #64748b;
    }

    .footer a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <main class="card">
    <header class="header">
      <h1>LIFE QUEST</h1>
      <p>Please enter your details to sign in.</p>
    </header>

    <form action="#" method="POST">
      <div class="form-group">
        <label for="email">Email address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          placeholder="name@example.com" 
          required 
          autocomplete="email" 
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          placeholder="••••••••" 
          required 
          autocomplete="current-password" 
        />
      </div>

      <div class="options">
        <label class="remember-me">
          <input type="checkbox" name="remember" />
          <span>Remember me</span>
        </label>
        <a href="#" class="forgot-password">Forgot password?</a>
      </div>

      <button type="submit" class="btn-submit">Sign in</button>
    </form>

    <footer class="footer">
      Don't have an account? <a href="#">Sign up</a>
    </footer>
    
<body>

    <h2>Login</h2>

    <form id="loginForm">
        <input type="text" id="loginUsername" placeholder="Username" required>
        <br><br>

        <input type="password" id="loginPassword" placeholder="Password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account?
        <a href="register.php">Register here</a>
    </p>

    <script src="script.js"></script>
</body>
</html>

</main>










</body>
</html>
