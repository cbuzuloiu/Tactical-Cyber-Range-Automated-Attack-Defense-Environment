<?php
// Core Routing Engine
// Default to the 'our_company' page if no parameter is provided
$page = isset($_GET['page']) ? $_GET['page'] : 'our_company';

// Strict Whitelist Matrix
// Explicitly define which pages are permitted to load within the master wrapper layout.
$allowed_pages = ['our_company', 'portfolio', 'our_team', 'contact_us'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Power Systems | Engineering</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&family=Syncopate:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background-grid"></div>

    <nav class="navbar">
        <div class="logo">APEX<span>_PWR</span></div>
        <ul class="nav-links">
            <li><a href="?page=our_company" class="<?= ($page == 'our_company') ? 'active' : '' ?>">Our Company</a></li>
            <li><a href="?page=portfolio" class="<?= ($page == 'portfolio') ? 'active' : '' ?>">Portfolio</a></li>
            <li><a href="?page=our_team" class="<?= ($page == 'our_team') ? 'active' : '' ?>">Our Team</a></li>
            <li><a href="?page=contact_us" class="<?= ($page == 'contact_us') ? 'active' : '' ?>">Contact Us</a></li>
        </ul>
    </nav>

    <main class="glass-container">
        <?php
            // Check if the requested parameter exists within our strict whitelist array
            if (in_array($page, $allowed_pages)) {
                $file = $page . '.php';
                if (file_exists($file)) {
                    include($file);
                } else {
                    echo "<div class='error-state'><h2>Error 404</h2><p>Grid Sector Offline.</p></div>";
                }
            } else {
                // For safely handling non-whitelisted pages (e.g., index, login, or LFI attempts)
                echo "<div class='error-state'><h2>Error 404</h2><p>Grid Sector Offline.</p></div>";
            }
        ?>
    </main>
</body>
</html>
