<?php
// login.php - Grid Operations Authentication Gateway
session_start();
include 'users.php';

// If already authenticated, pass them straight through to the portal
if (isset($_SESSION['operator'])) {
    header("Location: firmware_portal.php");
    exit();
}

$auth_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!array_key_exists($username, $user_database)) {
        $auth_message = "<div class='auth-error'>[ERR_AUTH] Invalid operator ID.</div>";
        error_log("[APEX_AUTH_FAIL] Context: PERIMETER_BRUTE | Target User: " . $username . " | Reason: INVALID_USER");
    } else {
        if ($user_database[$username] === $password) {
            // Establish the state baseline inside server memory
            $_SESSION['operator'] = $username;
            
            // Redirect the client execution path directly into the firmware utility
            header("Location: firmware_portal.php");
            exit();
        } else {
            $auth_message = "<div class='auth-error'>[ERR_AUTH] Incorrect authentication key.</div>";
            error_log("[APEX_AUTH_FAIL] Context: ACCOUNT_SPRAY | Target User: " . $username . " | Reason: INCORRECT_PASSWORD");
        }
    }
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
        body { justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #050608; }
        .login-container { width: 100%; max-width: 450px; padding: 50px 40px; background: var(--surface-color); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border: 1px solid rgba(0, 240, 255, 0.3); border-radius: 12px; box-shadow: 0 0 40px rgba(0, 240, 255, 0.1); }
        .auth-header { text-align: center; margin-bottom: 30px; }
        .auth-header h1 { font-size: 1.5rem; margin-bottom: 5px; color: #fff; letter-spacing: 2px; family: var(--font-display); }
        .auth-header p { color: var(--electric-blue); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; color: var(--text-muted); font-size: 0.8rem; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
        .input-group input { width: 100%; padding: 12px; background: rgba(0, 0, 0, 0.5); border: 1px solid var(--surface-border); border-radius: 4px; color: #fff; font-family: var(--font-sans); transition: all 0.3s ease; }
        .input-group input:focus { outline: none; border-color: var(--electric-blue); box-shadow: 0 0 10px rgba(0, 240, 255, 0.2); }
        .btn-auth { width: 100%; padding: 15px; margin-top: 10px; background: transparent; color: var(--electric-blue); border: 1px solid var(--electric-blue); border-radius: 4px; font-family: var(--font-display); font-size: 0.9rem; cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s ease; }
        .btn-auth:hover { background: var(--electric-blue); color: #000; box-shadow: 0 0 20px rgba(0, 240, 255, 0.4); }
        .auth-error { color: #ff3366; background: rgba(255, 51, 102, 0.1); padding: 12px; border: 1px solid #ff3366; border-radius: 4px; margin-bottom: 20px; font-size: 0.85rem; text-align: center; }
        .warning-banner { margin-top: 30px; text-align: center; font-size: 0.7rem; color: var(--text-muted); line-height: 1.5; border-top: 1px solid var(--surface-border); padding-top: 20px; }
        .success-screen h2 { font-family: var(--font-display); color: #00ffcc; text-shadow: 0 0 15px rgba(0,255,204,0.4); margin-bottom: 20px; }
        .terminal-readout { background: #000; padding: 20px; border-radius: 6px; border: 1px solid var(--surface-border); font-family: monospace; color: #a3e635; text-align: left; font-size: 0.85rem; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="background-grid"></div>

    <div class="login-container">
        <?php if ($success_state): ?>
            <div class="success-screen" style="text-align: center;">
                <h2>LINK ESTABLISHED</h2>
                <div class="terminal-readout">
                    <span style="color: #fff;">OPERATOR:</span> <?= $operator_id ?><br>
                    <span style="color: #fff;">CLEARANCE:</span> LEVEL 4 (GRID_MONITOR)<br>
                    <span style="color: #fff;">STATUS:</span> ENCRYPTED SESSION ACTIVE<br><br>
                    <span style="color: var(--electric-blue);">[system] Welcome back to the Aegis Hub.</span>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-header">
                <h1>APEX<span>_PWR</span></h1>
                <p>Level 4 Grid Operations</p>
            </div>

            <?= $auth_message ?>

            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="username">Operator ID</label>
                    <input type="text" id="username" name="username" required autocomplete="off" placeholder="e.g., r.mercer">
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
        <?php endif; ?>
    </div>
</body>
</html>
