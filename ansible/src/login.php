<?php
// Core Authentication Gateway - Frontend Only
// Logic and vulnerabilities to be implemented in Phase 3
$auth_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Placeholder logic - currently rejects all input
    $auth_message = "<div style='color: #ff3366; background: rgba(255, 51, 102, 0.1); padding: 10px; border: 1px solid #ff3366; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;'>[ERR_04] Authentication Server Offline. Please use local override.</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Power | Grid Operations</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&family=Syncopate:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Specific overrides to center the login portal perfectly */
        body {
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #050608; /* Slightly darker than public site */
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 50px 40px;
            background: var(--surface-color);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 240, 255, 0.3); /* Stronger blue border */
            border-radius: 12px;
            box-shadow: 0 0 40px rgba(0, 240, 255, 0.1);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-header h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #fff;
            letter-spacing: 2px;
        }
        .auth-header p {
            color: var(--electric-blue);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--surface-border);
            border-radius: 4px;
            color: #fff;
            font-family: var(--font-sans);
            transition: all 0.3s ease;
        }
        .input-group input:focus {
            outline: none;
            border-color: var(--electric-blue);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
        }
        .btn-auth {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            background: transparent;
            color: var(--electric-blue);
            border: 1px solid var(--electric-blue);
            border-radius: 4px;
            font-family: var(--font-display);
            font-size: 0.9rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }
        .btn-auth:hover {
            background: var(--electric-blue);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        }
        .warning-banner {
            margin-top: 30px;
            text-align: center;
            font-size: 0.7rem;
            color: var(--text-muted);
            line-height: 1.5;
            border-top: 1px solid var(--surface-border);
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="background-grid"></div>

    <div class="login-container">
        <div class="auth-header">
            <h1>APEX<span>_PWR</span></h1>
            <p>Level 4 Grid Operations</p>
        </div>

        <?= $auth_message ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Operator ID</label>
                <input type="text" id="username" name="username" required autocomplete="off" placeholder="e.g., a.sterling">
            </div>
            
            <div class="input-group">
                <label for="password">Authentication Key</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-auth">Initialize Link</button>
        </form>

        <div class="warning-banner">
            UNAUTHORIZED ACCESS IS STRICTLY PROHIBITED.<br>
            All telemetry and authentication attempts are logged and monitored by the Aegis Defense Network.
        </div>
    </div>
</body>
</html>
