<?php
session_start();
include __DIR__ . '/../includes/logindb.php';

$view_id = null;
$is_authenticated = isset($_SESSION["user_id"]);

if ($is_authenticated) {
    $view_id = $_SESSION["user_id"];
    $back_link = 'dashboard.php';
    $back_text = 'Back to Dashboard';
} else {
    $view_id = 1; 
    $back_link = '../index.php';
    $back_text = 'Back to Home';
}

$profile = pg_fetch_assoc(pg_query_params($connection, "SELECT * FROM profiles WHERE user_id = $1", [$view_id]));

if (!$profile) {
    die("Resume profile not found.");
}

$skills = pg_fetch_all(pg_query_params($connection, "SELECT * FROM skills WHERE user_id = $1 ORDER BY id", [$view_id])) ?: [];
$education = pg_fetch_all(pg_query_params($connection, "SELECT * FROM educations WHERE user_id = $1 ORDER BY id", [$view_id])) ?: [];
$projects = pg_fetch_all(pg_query_params($connection, "SELECT * FROM projects WHERE user_id = $1 ORDER BY id", [$view_id])) ?: [];

pg_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/resumeStyles.css">
    <title><?php echo htmlspecialchars($profile['fullname'] ?? 'Resume'); ?></title>
</head>

<body>
    <div class="container">
        <header>
            <div class="profile-pic">
                <img src="<?php echo htmlspecialchars($profile['profile_picture'] ?? 'assets/images/default-profile.png'); ?>" alt="profile picture">
            </div>
        </header>
        <main>
            <h1>
                <?php echo htmlspecialchars($profile['fullname'] ?? ''); ?>
            </h1>

                <section id="contact">
                    <p>
                        <a href="mailto:<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
                            <img src="assets/images/mail.png" alt="Email icon" width="22px">
                            <?php echo htmlspecialchars($profile['email'] ?? ''); ?>
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
                            <img src="assets/images/phone.png" alt="Phone icon" width="22px">
                            <?php echo htmlspecialchars($profile['phone'] ?? ''); ?>
                        </a>
                        <span>
                            <img src="assets/images/location.png" alt="Location icon" width="22px">
                            <?php echo htmlspecialchars($profile['location'] ?? ''); ?>
                        </span>
                    </p>
                </section>

                <hr>

                <section>
                <p>
                    <?php echo nl2br(htmlspecialchars($profile['summary'] ?? '')); ?>
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
                            echo "<li>" . htmlspecialchars($skill["skill_name"]) . "</li>";
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
                <a href="<?php echo $back_link; ?>" class="btn-back"><?php echo $back_text; ?></a>
                <a href="assets/resume/Busque-Resume.pdf" class="btn-download" download>Download Resume</a>
            </div>
        </footer>
    </div>
</body>
</html>