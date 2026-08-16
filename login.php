<?php

session_start();

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter both email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, full_name, email, password
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["full_name"];
                $_SESSION["user_email"] = $user["email"];

                header("Location: index.php");

                exit();

            } else {

                $message = "Invalid email or password.";
            }

        } else {

            $message = "Invalid email or password.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>EventHub - Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        EventHub
    </div>

    <div class="nav-links">

        <a href="index.php">
            Home
        </a>

        <a href="register.php">
            Register
        </a>

        <a href="login.php">
            Login
        </a>

    </div>

</nav>


<!-- LOGIN -->

<div class="login-container">

    <h1>
        Welcome Back
    </h1>

    <p class="form-description">
        Login to your EventHub account.
    </p>


    <?php if (!empty($message)): ?>

        <div class="error">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php endif; ?>


    <form
        method="POST"
        action="login.php"
    >

        <label for="email">
            Email Address
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
        >


        <label for="password">
            Password
        </label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
        >


        <button type="submit">
            Login
        </button>

    </form>


    <div class="bottom-text">

        Don't have an account?

        <a href="register.php">
            Register here
        </a>

    </div>

</div>


<footer>

    <p>
        © 2026 EventHub | DevOps Lab Project
    </p>

</footer>

</body>

</html>