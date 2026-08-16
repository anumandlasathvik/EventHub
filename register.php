<?php

session_start();

require_once "db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $college = trim($_POST["college"]);
    $password = $_POST["password"];

    $event_name = trim($_POST["event_name"]);
    $event_date = $_POST["event_date"];
    $gender = $_POST["gender"];
    $comments = trim($_POST["comments"]);

    // Basic validation
    if (
        empty($full_name) ||
        empty($email) ||
        empty($phone) ||
        empty($college) ||
        empty($password) ||
        empty($event_name) ||
        empty($event_date) ||
        empty($gender)
    ) {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } else {

        // Check whether email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
            $message_type = "error";

        } else {

            // Hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Start transaction
            $conn->begin_transaction();

            try {

                // Insert user
                $stmt = $conn->prepare(
                    "INSERT INTO users
                    (full_name, email, phone, college, password)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssss",
                    $full_name,
                    $email,
                    $phone,
                    $college,
                    $hashed_password
                );

                $stmt->execute();

                $user_id = $conn->insert_id;

                // Insert event registration
                $event_stmt = $conn->prepare(
                    "INSERT INTO event_registrations
                    (user_id, event_name, event_date, gender, comments)
                    VALUES (?, ?, ?, ?, ?)"
                );

                $event_stmt->bind_param(
                    "issss",
                    $user_id,
                    $event_name,
                    $event_date,
                    $gender,
                    $comments
                );

                $event_stmt->execute();

                // Commit transaction
                $conn->commit();

                $message = "Registration successful! You can now login.";
                $message_type = "success";

            } catch (Exception $e) {

                $conn->rollback();

                $message = "Registration failed. Please try again.";
                $message_type = "error";
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EventHub - Registration</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        EventHub
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>

    </div>

</nav>


<!-- REGISTRATION FORM -->

<div class="form-container">

    <h1>Event Registration</h1>

    <p class="form-description">
        Create your account and register for an event.
    </p>


    <?php if (!empty($message)): ?>

        <div class="<?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <form method="POST" action="register.php">


        <!-- PERSONAL DETAILS -->

        <h2>Personal Information</h2>


        <label for="full_name">
            Full Name *
        </label>

        <input
            type="text"
            id="full_name"
            name="full_name"
            placeholder="Enter your full name"
            required
        >


        <label for="email">
            Email Address *
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email"
            required
        >


        <label for="phone">
            Phone Number *
        </label>

        <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="Enter your phone number"
            pattern="[0-9]{10}"
            required
        >


        <label for="college">
            College / University *
        </label>

        <input
            type="text"
            id="college"
            name="college"
            placeholder="Enter your college name"
            required
        >


        <label for="password">
            Password *
        </label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Create a password"
            minlength="6"
            required
        >


        <!-- EVENT DETAILS -->

        <h2>Event Information</h2>


        <label for="event_name">
            Select Event *
        </label>

        <select
            id="event_name"
            name="event_name"
            required
        >

            <option value="">
                -- Select an Event --
            </option>

            <option value="Technical Workshop">
                Technical Workshop
            </option>

            <option value="Hackathon">
                Hackathon
            </option>

            <option value="Technical Seminar">
                Technical Seminar
            </option>

        </select>


        <label for="event_date">
            Event Date *
        </label>

        <input
            type="date"
            id="event_date"
            name="event_date"
            required
        >


        <label for="gender">
            Gender *
        </label>

        <select
            id="gender"
            name="gender"
            required
        >

            <option value="">
                -- Select Gender --
            </option>

            <option value="Male">
                Male
            </option>

            <option value="Female">
                Female
            </option>

            <option value="Other">
                Other
            </option>

        </select>


        <label for="comments">
            Comments / Requirements
        </label>

        <textarea
            id="comments"
            name="comments"
            rows="4"
            placeholder="Any additional information..."
        ></textarea>


        <button type="submit">
            Register for Event
        </button>

    </form>


    <div class="bottom-text">

        Already have an account?

        <a href="login.php">
            Login here
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