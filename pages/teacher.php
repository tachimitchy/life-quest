
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e8f0fe;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #1a3c70;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #146c43;
        }

        #message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Create Account</h2>

    <form id="registrationForm">

        <label>Full Name</label>
        <input type="text" id="fullname"
        placeholder="Enter your full name" required>

        <label>Email Address</label>
        <input type="email" id="email"
        placeholder="Enter your email address" required>

        <label>Password</label>
        <input type="password" id="password"
        placeholder="Enter your password" required>

        <label>Confirm Password</label>
        <input type="password" id="confirmPassword"
        placeholder="Confirm your password" required>

        <label>Code</label>
        <input type="code" id="code"
        placeholder="Enter your code" required>

        <button type="submit">Create Account</button>
         <footer class="footer">
      Already have an account? <a href="#">Login</a>
    </footer>

        <p id="message"></p>

    </form>

</div>

<script>

    document.getElementById("createForm")
    .addEventListener("submit", function(event) {

        event.preventDefault();

        let fullname = document.getElementById("fullname").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        let code = document.getElementById("code").value;

        let message = document.getElementById("message");

        if (password !== confirmPassword) {

            message.style.color = "red";
            message.innerHTML = "Passwords do not match!";

        } else {

            message.style.color = "green";
            message.innerHTML = "Teacher account created successfully!";

            console.log("Full Name: " + fullname);
            console.log("Email Address: " + email);

            document.getElementById("createForm").reset();
        }

    });

</script>

</body>
</html>
