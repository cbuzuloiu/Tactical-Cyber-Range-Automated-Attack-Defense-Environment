<?php
// Dummy form processing for the lab environment
$form_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real scenario, this would send an email. Here, we just provide a tactile response.
    $form_status = "<div class='success-msg' style='padding: 15px; margin-bottom: 20px; border-left: 4px solid #00ff00; background: rgba(0, 255, 0, 0.05); color: #00ff00;'>Message transmitted securely. A telemetry agent will respond within 24 hours.</div>";
}
?>
<div class="contact-wrapper">
    <h1>Establish Connection.</h1>
    <p style="margin-bottom: 40px; font-size: 1.1rem;">
        For inquiries regarding grid architecture, enterprise SCADA deployment, or emergency telemetry support, please use the encrypted channel below.
    </p>

    <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        
        <div class="form-section">
            <?= $form_status ?>
            <form method="POST" action="?page=contact_us" style="display: flex; flex-direction: column; gap: 20px;">
                <input type="text" name="sender_name" placeholder="Full Name / Designation" required style="width: 100%; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--surface-border); color: #fff; padding: 15px; border-radius: 6px; font-family: var(--font-sans);">
                
                <input type="email" name="sender_email" placeholder="Corporate Email" required style="width: 100%; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--surface-border); color: #fff; padding: 15px; border-radius: 6px; font-family: var(--font-sans);">
                
                <input type="text" name="subject" placeholder="Subject / Sector" required style="width: 100%; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--surface-border); color: #fff; padding: 15px; border-radius: 6px; font-family: var(--font-sans);">
                
                <textarea name="message" placeholder="Encrypted Transmission..." required style="width: 100%; background: rgba(255, 255, 255, 0.03); border: 1px solid var(--surface-border); color: #fff; padding: 15px; border-radius: 6px; min-height: 150px; font-family: var(--font-sans); resize: vertical;"></textarea>
                
                <button type="submit" style="padding: 15px; background: transparent; color: var(--electric-blue); border: 1px solid var(--electric-blue); border-radius: 6px; font-family: var(--font-display); font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 2px;">
                    Transmit Data
                </button>
            </form>
        </div>

        <div class="info-section" style="padding-left: 20px; border-left: 1px solid var(--surface-border);">
            <h3 style="color: #fff; margin-bottom: 20px; font-family: var(--font-display); font-size: 1.2rem;">Global Command Centers</h3>
            
            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--electric-blue); margin-bottom: 5px;">North American Sector (HQ)</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    Building 4, Silicon Grid Park<br>
                    San Jose, CA 95134<br>
                    United States
                </p>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--electric-blue); margin-bottom: 5px;">European Sector</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">
                    Technoparkstrasse 1<br>
                    8005 Zürich<br>
                    Switzerland
                </p>
            </div>

            <div style="margin-top: 40px; padding: 20px; background: rgba(255, 51, 102, 0.05); border: 1px solid rgba(255, 51, 102, 0.3); border-radius: 8px;">
                <h4 style="color: #ff3366; margin-bottom: 10px; font-size: 0.9rem; text-transform: uppercase;">Emergency Grid Override</h4>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                    In the event of a catastrophic telemetry failure, authorized personnel must utilize the dedicated out-of-band communication protocols. Do not use this web form for Level 1 critical incidents.
                </p>
            </div>
        </div>

    </div>

    <style>
        button[type="submit"]:hover {
            background: var(--electric-blue);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        }
    </style>
</div>
