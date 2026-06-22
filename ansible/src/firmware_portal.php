<?php
// firmware_portal.php - Aegis Substation Management Utility
session_start();

// Enforce Session Boundary: Check if the operator has authenticated
if (!isset($_SESSION['operator'])) {
    header("Location: login.php");
    exit();
}

$upload_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["firmware_blob"])) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES["firmware_blob"]["name"]);
    $target_path = $target_dir . $file_name;

    // VULNERABILITY ARCHITECTURE: Complete omission of validation mechanics.
    // The engine does not perform extension filtering (.php passes natively) or MIME-type inspection.
    if (move_uploaded_file($_FILES["firmware_blob"]["tmp_name"], $target_path)) {
        $upload_message = "<div class='upload-success'>[SUCCESS] Matrix deployed to sector. Path: <a href='" . htmlspecialchars($target_path) . "' target='_blank'>/" . htmlspecialchars($target_path) . "</a></div>";
        
        // System log generation for EDR/Filebeat hunting
        error_log("[APEX_SYS_UPDATE] Context: FIRMWARE_UPLOAD | Operator: " . $_SESSION['operator'] . " | Artifact: " . $file_name . " | Status: COMMITTED");
    } else {
        $upload_message = "<div class='upload-error'>[ERR_SYS] Inbound stream interrupted. Transfer failed.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis Hub | Substation Telemetry</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&family=Syncopate:wght@700&display=swap" rel="stylesheet">
    <style>
        body { display: flex; flex-direction: column; align-items: center; padding-top: 40px; background-color: #050608; }
        .control-panel { width: 100%; max-width: 900px; padding: 40px; background: var(--surface-color); backdrop-filter: blur(15px); border: 1px solid var(--surface-border); border-radius: 12px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--surface-border); padding-bottom: 20px; margin-bottom: 30px; }
        .panel-header h1 { font-size: 1.4rem; margin: 0; font-family: var(--font-display); }
        .operator-badge { font-size: 0.8rem; padding: 6px 12px; background: rgba(0, 240, 255, 0.1); border: 1px solid var(--electric-blue); color: var(--electric-blue); border-radius: 4px; font-family: monospace; }
        .upload-zone { background: rgba(0,0,0,0.4); border: 2px dashed rgba(0, 240, 255, 0.2); padding: 40px; text-align: center; border-radius: 8px; margin-bottom: 30px; }
        .upload-zone p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px; }
        .file-input-wrapper input[type="file"] { color: var(--text-muted); margin-bottom: 20px; }
        .btn-deploy { padding: 12px 30px; background: transparent; color: var(--electric-blue); border: 1px solid var(--electric-blue); border-radius: 4px; font-family: var(--font-display); font-size: 0.8rem; letter-spacing: 1px; cursor: pointer; transition: all 0.3s ease; }
        .btn-deploy:hover { background: var(--electric-blue); color: #000; box-shadow: 0 0 15px rgba(0, 240, 255, 0.3); }
        .upload-success { color: #00ffcc; background: rgba(0, 255, 204, 0.05); border: 1px solid #00ffcc; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
        .upload-success a { color: #fff; text-decoration: underline; font-weight: bold; }
        .upload-error { color: #ff3366; background: rgba(255, 51, 102, 0.05); border: 1px solid #ff3366; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
        .logout-link { color: var(--text-muted); text-decoration: none; font-size: 0.85rem; border: 1px solid var(--surface-border); padding: 6px 12px; border-radius: 4px; transition: all 0.3s ease; }
        .logout-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    <div class="background-grid"></div>

    <div class="control-panel">
        <div class="panel-header">
            <div>
                <h1>AEGIS // GRID MANAGEMENT</h1>
                <p style="color: var(--electric-blue); font-size: 0.8rem; margin: 5px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">PLC & Firmware Distribution Module</p>
            </div>
            <div style="display: flex; gap: 15px; align-items: center;">
                <span class="operator-badge">OP_ID: <?= htmlspecialchars($_SESSION['operator']) ?></span>
                <a href="logout.php" class="logout-link">Disconnect</a>
            </div>
        </div>

        <?= $upload_message ?>

        <div class="utility-descriptor" style="margin-bottom: 30px;">
            <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 10px;">Substation Node Flash Utility</h3>
            <p style="font-size: 0.9rem; margin: 0;">
                Select the pre-compiled ladder logic payload or relay binary configuration file to distribute to regional substation controllers. 
                Ensure checksum hashes match technical release profiles prior to launching distribution cycles.
            </p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="upload-zone">
                <p>Prepare binary configuration block target stream (.bin, .cfg, .dat)</p>
                <div class="file-input-wrapper">
                    <input type="file" name="firmware_blob" required>
                </div>
                <button type="submit" class="btn-deploy">Commit Firmware to Grid</button>
            </div>
        </form>
    </div>
</body>
</html>
