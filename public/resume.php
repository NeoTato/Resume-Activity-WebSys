<?php
session_start();
include __DIR__ . '/../includes/logindb.php'; // Include database connection

// 1. Authentication Check
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["user_id"];

// 2. Fetch Profile Data
$resultProfile = pg_query_params($connection, "SELECT * FROM profiles WHERE user_id = $1", [$user_id]);
$profile = pg_fetch_assoc($resultProfile);

if (!$profile) {
    // This can happen if the user ran setup/fix scripts for a different user ID
    die("Profile not found for this user. Please contact support or try running the setup script again.");
}

// Map database columns to the variables your HTML already uses
$fullname = $profile['fullname'];
$email = $profile['email'];
$phone = $profile['phone'];
$location = $profile['location'];
$shortinfo = $profile['summary'];
$profile_pic = $profile['profile_picture'] ?? 'assets/images/eon-profile-picture.png'; // Use default if DB is null

// 3. Fetch Skills
$resultSkills = pg_query_params($connection, "SELECT skill_name FROM skills WHERE user_id = $1 ORDER BY id", [$user_id]);
// pg_fetch_all_columns fetches just the first column into a simple array
$skills = pg_fetch_all_columns($resultSkills, 0) ?: []; 

// 4. Fetch Education
$resultEdu = pg_query_params($connection, "SELECT * FROM educations WHERE user_id = $1 ORDER BY start_year DESC", [$user_id]);
$education = pg_fetch_all($resultEdu) ?: []; // Fetches all rows as an associative array

// 5. Fetch Projects
$resultProj = pg_query_params($connection, "SELECT * FROM projects WHERE user_id = $1 ORDER BY id ASC", [$user_id]);
$projects = pg_fetch_all($resultProj) ?: [];

// Close the connection
pg_close($connection);

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
                <!-- Use the profile picture from the database -->
                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="profile picture">
            </div>
        </header>
        <main>
            <h1>
                <?php echo htmlspecialchars($fullname); ?>
            </h1>

                <section id="contact">
                    <p>
                        <a href="mailto:<?php echo htmlspecialchars($email); ?>">
                            <img src="assets/images/mail.png" alt="Email icon" width="22px">
                            <?php echo htmlspecialchars($email); ?>
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($phone); ?>">
                            <img src="assetsObject" notJSON-serializable"assets/images/phone.png" alt="Phone icon" width="22px">
                            <?php echo htmlspecialchars($phone); ?>
                        </a>
                        <span>
                            <img src="assets/images/location.png" alt="Location icon" width="22px">
                            <?php echo htmlspecialchars($location); ?>
                        </span>
                    </p>
                </section>

                <hr>

                <section>
                <p>
                    <!-- Use nl2br to respect line breaks from the database summary -->
                    <?php echo nl2br(htmlspecialchars($shortinfo));?>
                </p>
                </section>

                <hr>

                <section>
                        <h2>Projects</h2>
                            <dl>
                                <?php foreach ($projects as $project): ?>
                                    <dt><h3><?php echo htmlspecialchars($project["title"]); ?></h3></dt>
                                    <dd><?php echo htmlspecialchars($project["description"]); ?></dd>
                                <?php endforeach; ?>
                            </dl>
                </section>

                <hr>

                <section>
                    <h2> Skills </h2>
                    <ul> 
                        <?php
                        foreach ($skills as $skill) {
                            echo "<li>" . htmlspecialchars($skill) . "</li>";
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
                            <?php echo htmlspecialchars($edu["program"]); ?> 
                            (<?php echo htmlspecialchars($edu["start_year"]) . " - " . htmlspecialchars($edu["end_year"]); ?>)
                            </dt>
                            <dd>
                            <?php echo htmlspecialchars($edu["university"]); ?>
                            </dd>
                        <?php endforeach; ?>
                    </dl>
                </section>

                <hr>

        </main>
        <footer>
            <p>&copy; Eon Busque</p>
            <div class="footer-buttons">
                <a href="assets/resume/Busque-Resume.pdf" class="btn-download" download>Download Resume</a>
                <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
                <form action="logout.php" method="post">
                    <button type="submit" class="btn-logout">Logout</button>
                </form>

            </div>
        </footer>
    </div>
</body>
</html>