<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EventHub - Home</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVIGATION -->

<nav class="navbar">

    <div class="logo">
        EventHub
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <?php if (isset($_SESSION["user_id"])): ?>

            <a href="logout.php">Logout</a>

        <?php else: ?>

            <a href="register.php">Register</a>
            <a href="login.php">Login</a>

        <?php endif; ?>

    </div>

</nav>


<!-- HERO SECTION -->

<section class="hero">

    <div class="hero-content">

        <p class="tagline">
            CONNECT • LEARN • CREATE
        </p>

        <h1>
            Welcome to <span>EventHub</span>
        </h1>

        <p class="hero-text">
            Discover exciting technical events, workshops,
            seminars and hackathons. Register and be part
            of the experience.
        </p>

        <?php if (isset($_SESSION["user_id"])): ?>

            <p class="welcome">
                Welcome back,
                <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!
            </p>

            <a href="register.php" class="btn">
                Register for an Event
            </a>

        <?php else: ?>

            <div class="hero-buttons">

                <a href="register.php" class="btn">
                    Register Now
                </a>

                <a href="login.php" class="btn btn-outline">
                    Login
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- EVENTS -->

<section class="events">

    <div class="section-heading">

        <p class="tagline">
            WHAT'S HAPPENING
        </p>

        <h2>Upcoming Events</h2>

    </div>


    <div class="event-container">


        <div class="event-card">

            <div class="event-icon">
                💻
            </div>

            <h3>
                Technical Workshop
            </h3>

            <p>
                Learn modern technologies and
                practical development skills.
            </p>

            <span>
                Hands-on Learning
            </span>

        </div>


        <div class="event-card">

            <div class="event-icon">
                🚀
            </div>

            <h3>
                Hackathon
            </h3>

            <p>
                Build innovative solutions and
                compete with talented developers.
            </p>

            <span>
                Innovation &amp; Coding
            </span>

        </div>


        <div class="event-card">

            <div class="event-icon">
                🎓
            </div>

            <h3>
                Technical Seminar
            </h3>

            <p>
                Explore emerging technologies
                with industry experts.
            </p>

            <span>
                Knowledge &amp; Networking
            </span>

        </div>

    </div>

</section>


<!-- FOOTER -->

<footer>

    <p>
        © 2026 EventHub | DevOps Lab Project
    </p>

</footer>

</body>

</html>