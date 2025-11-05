<?php
session_start();
include __DIR__ . '/../includes/logindb.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["user_id"];

$success_message = "";
$error_message = "";

function checkQuery($result, $connection) {
    if (!$result) {
        throw new \Exception(pg_last_error($connection));
    }
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        checkQuery(pg_query($connection, "BEGIN"), $connection);

        $queryProfile = "
            UPDATE profiles 
            SET fullname = $1, email = $2, phone = $3, location = $4, summary = $5, profile_picture = $6
            WHERE user_id = $7";
        
        $resultProfile = pg_query_params($connection, $queryProfile, [
            $_POST['fullname'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['location'],
            $_POST['summary'],
            $_POST['profile_pic'],
            $user_id
        ]);
        checkQuery($resultProfile, $connection);

        checkQuery(pg_query_params($connection, "DELETE FROM skills WHERE user_id = $1", [$user_id]), $connection);
        if (!empty($_POST['skills'])) {
            foreach ($_POST['skills'] as $skill) {
                if (!empty(trim($skill))) {
                    checkQuery(pg_query_params($connection, "INSERT INTO skills (user_id, skill_name) VALUES ($1, $2)", [$user_id, $skill]), $connection);
                }
            }
        }

        checkQuery(pg_query_params($connection, "DELETE FROM educations WHERE user_id = $1", [$user_id]), $connection);
        if (!empty($_POST['edu_program'])) {
            for ($i = 0; $i < count($_POST['edu_program']); $i++) {
                if (!empty(trim($_POST['edu_program'][$i]))) {
                    
                    $start_year = !empty($_POST['edu_start'][$i]) ? (int)$_POST['edu_start'][$i] : null;
                    $end_year = !empty($_POST['edu_end'][$i]) ? (int)$_POST['edu_end'][$i] : null;

                    checkQuery(pg_query_params($connection, "INSERT INTO educations (user_id, program, university, start_year, end_year) VALUES ($1, $2, $3, $4, $5)", [
                        $user_id,
                        $_POST['edu_program'][$i],
                        $_POST['edu_university'][$i],
                        $start_year,
                        $end_year
                    ]), $connection);
                }
            }
        }

        checkQuery(pg_query_params($connection, "DELETE FROM projects WHERE user_id = $1", [$user_id]), $connection);
        if (!empty($_POST['project_title'])) {
            for ($i = 0; $i < count($_POST['project_title']); $i++) {
                if (!empty(trim($_POST['project_title'][$i]))) {
                    checkQuery(pg_query_params($connection, "INSERT INTO projects (user_id, title, description) VALUES ($1, $2, $3)", [
                        $user_id,
                        $_POST['project_title'][$i],
                        $_POST['project_desc'][$i]
                    ]), $connection);
                }
            }
        }

        checkQuery(pg_query($connection, "COMMIT"), $connection);
        $success_message = "Resume updated successfully!";

    } catch (\Exception $e) {
        pg_query($connection, "ROLLBACK");
        $error_message = "Update Failed: " . $e->getMessage();
    }
}

$profile = pg_fetch_assoc(pg_query_params($connection, "SELECT * FROM profiles WHERE user_id = $1", [$user_id]));
$skills = pg_fetch_all(pg_query_params($connection, "SELECT * FROM skills WHERE user_id = $1 ORDER BY id", [$user_id])) ?: [];
$education = pg_fetch_all(pg_query_params($connection, "SELECT * FROM educations WHERE user_id = $1 ORDER BY id", [$user_id])) ?: [];
$projects = pg_fetch_all(pg_query_params($connection, "SELECT * FROM projects WHERE user_id = $1 ORDER BY id", [$user_id])) ?: [];

pg_close($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume</title>
    <link rel="stylesheet" href="assets/css/resumeStyles.css">
    <link rel="stylesheet" href="assets/css/editStyles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Your Resume</h1>
        <p>Changes saved here will be visible on your public resume page.</p>
        
        <div class="dashboard-link-container">
            <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit_resume.php" method="POST" class="edit-form">
            
            <h2>Profile</h2>
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($profile['fullname'] ?? ''); ?>">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
            
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
            
            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>">
            
            <label for="profile_pic">Profile Picture URL</label>
            <input type="text" id="profile_pic" name="profile_pic" value="<?php echo htmlspecialchars($profile['profile_picture'] ?? ''); ?>">

            <label for="summary">Summary</label>
            <textarea id="summary" name="summary" rows="5"><?php echo htmlspecialchars($profile['summary'] ?? ''); ?></textarea>

            <hr class="form-divider">

            <h2>Skills</h2>
            <div id="skills-container">
                <?php foreach ($skills as $skill): ?>
                    <div class="dynamic-item">
                        <input type="text" name="skills[]" value="<?php echo htmlspecialchars($skill['skill_name']); ?>" placeholder="e.g., Python">
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add" onclick="addSkill()">Add Skill</button>
            
            <hr class="form-divider">

            <h2>Education</h2>
            <div id="education-container">
                <?php foreach ($education as $edu): ?>
                    <div class="dynamic-item">
                        <div class="item-fields">
                            <label>Program/Degree</label>
                            <input type="text" name="edu_program[]" value="<?php echo htmlspecialchars($edu['program']); ?>" placeholder="Program/Degree">
                            <label>University/School</label>
                            <input type="text" name="edu_university[]" value="<?php echo htmlspecialchars($edu['university']); ?>" placeholder="University/School">
                            <label>Start Year</label>
                            <input type="number" name="edu_start[]" value="<?php echo htmlspecialchars($edu['start_year']); ?>" placeholder="Start Year (e.g., 2020)">
                            <label>End Year</label>
                            <input type="number" name="edu_end[]" value="<?php echo htmlspecialchars($edu['end_year']); ?>" placeholder="End Year (e.g., 2024 or 0 for Present)">
                        </div>
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add" onclick="addEducation()">Add Education</button>

            <hr class="form-divider">

            <h2>Projects</h2>
            <div id="projects-container">
                <?php foreach ($projects as $proj): ?>
                    <div class="dynamic-item">
                        <div class="item-fields">
                            <label>Project Title</label>
                            <input type="text" name="project_title[]" value="<?php echo htmlspecialchars($proj['title']); ?>" placeholder="Project Title">
                            <label>Description</label>
                            <textarea name="project_desc[]" placeholder="Project Description"><?php echo htmlspecialchars($proj['description']); ?></textarea>
                        </div>
                        <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add" onclick="addProject()">Add Project</button>
            
            <button type="submit" class="btn-save">Save All Changes</button>
        </form>
    </div>

    <script>
        function removeItem(button) {
            button.closest('.dynamic-item').remove();
        }

        function addSkill() {
            const container = document.getElementById('skills-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <input type="text" name="skills[]" placeholder="e.g., New Skill">
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }

        function addEducation() {
            const container = document.getElementById('education-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <div class="item-fields">
                    <label>Program/Degree</label>
                    <input type="text" name="edu_program[]" placeholder="Program/Degree">
                    <label>University/School</label>
                    <input type="text" name="edu_university[]" placeholder="University/School">
                    <label>Start Year</label>
                    <input type="number" name="edu_start[]" placeholder="Start Year (e.g., 2020)">
                    <label>End Year</label>
                    <input type="number" name="edu_end[]" placeholder="End Year (e.g., 2024 or 0 for Present)">
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }

        function addProject() {
            const container = document.getElementById('projects-container');
            const newItem = document.createElement('div');
            newItem.className = 'dynamic-item';
            newItem.innerHTML = `
                <div class="item-fields">
                    <label>Project Title</label>
                    <input type="text" name="project_title[]" placeholder="Project Title">
                    <label>Description</label>
                    <textarea name="project_desc[]" placeholder="Project Description"></textarea>
                </div>
                <button type="button" class="btn-remove" onclick="removeItem(this)">Remove</button>
            `;
            container.appendChild(newItem);
        }
    </script>
</body>
</html>