<?php
    session_start();

    // If the user is not logged in, redirect to login page
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    }

    $fullname = "Eon Maxell P. Busque";
    $email = "emp.busque38@gmail.com";
    $phone = "+63 976 054 2971";
    $location = "Tayabas City, Quezon, Philippines";
    $shortinfo = "A third-year college student taking Computer Science in Batangas State University who has a passion for software development. <br> <br> Currently learning Php and Flutter.";
    $skills = ["Python", "Java", "HTML", "CSS", "MySQL", "C#"];
    $education = [
        [
        "program" => "Bachelor of Science in Computer Science",
        "start_year" => "2023",
        "end_year" => "2027",
        "university" => "Batangas State University - The National Engineering University - Alangilan"
        ]
    ];

    $projects = [
        [
        "title" => "Wellness Tracker",
        "description" => "A wellness tracker integrating MySQL to monitor meals, workouts, and sleep, promoting health and well-being."
        ],

        [
        "title" => "AGAPAY: Arduino-based Water Agitator",
        "description" => "Designed and developed an Arduino-powered water agitator system with a team, focusing on backend using C++ and Python."
        ],
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/resumeStyles.css" type="text/css">
    <title>My Resume</title>
</head>

<body>
    <div class="container">
        <header>
            <div class="profile-pic">
                <img src="assets/images/eon-profile-picture.png" alt="eon profile picture">
            </div>
        </header>
        <main>
            <h1>
                <?php echo $fullname?>
            </h1>

                <section id="contact">
                    <p>
                        <a href="mailto:emp.busque38@gmail.com">
                            <img src="assets/images/mail.png" alt="Email icon" width="22px">
                            <?php echo "$email "; ?>
                        </a>
                        <a href="tel:09760542971">
                            <img src="assets/images/phone.png" alt="Phone icon" width="22px">
                            <?php echo "$phone"; ?>
                        </a>
                        <span>
                            <img src="assets/images/location.png" alt="Location icon" width="22px">
                            <?php echo "$location"; ?>
                        </span>
                    </p>
                </section>

                <hr>

                <section>
                <p>
                    <?php echo "$shortinfo";?>
                </p>
                </section>

                <hr>

                <section>
                        <h2>Projects</h2>
                            <dl>
                                <?php foreach ($projects as $project): ?>
                                    <dt><h3><?php echo $project["title"]; ?></h3></dt>
                                    <dd><?php echo $project["description"]; ?></dd>
                                <?php endforeach; ?>
                            </dl>
                </section>

                <hr>

                <section>
                    <h2> Skills </h2>
                    <ul> 
                        <?php
                        foreach ($skills as $skill) {
                            echo "<li>" . $skill . "</li>";
                        }
                        ?>
                    </ul>
                </section>
                
                <hr>

                <section>
                    <h2>Education</h2>
                    <dl>
                        <?php foreach ($education as $edu): ?>
                            <dt>
                            <?php echo $edu["program"]; ?> 
                            (<?php echo $edu["start_year"] . " - " . $edu["end_year"]; ?>)
                            </dt>
                            <dd>
                            <?php echo $edu["university"]; ?>
                            </dd>
                        <?php endforeach; ?>
            </dl>
                </section>

                <hr>

        </main>
        <footer>
            <p>&copy; Eon Busque</p>
            <form action="logout.php" method="post" style="text-align:center; margin-top:20px;">
                <button type="submit">Logout</button>
            </form>

        </footer>
    </div>
</body>
</html>