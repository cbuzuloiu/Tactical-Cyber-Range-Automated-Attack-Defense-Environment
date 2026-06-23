<?php
// Core Routing Engine
// Default to the 'our_company' page if no parameter is provided
$page = isset($_GET['page']) ? $_GET['page'] : 'our_company';
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
            // Security Note: Currently securely loading local files.
            // This is where we can open the gates later if an LFI is desired.
            $file = $page . '.php';
            if(file_exists($file)) {
                include($file);
            } else {
                echo "<div class='error-state'><h2>Error 404</h2><p>Grid Sector Offline.</p></div>";
            }
        ?>
    </main>
</body>
</html>
