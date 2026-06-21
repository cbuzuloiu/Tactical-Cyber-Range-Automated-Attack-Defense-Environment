<div class="portfolio-wrapper">
    <h1>Operational Portfolio.</h1>
    <p style="margin-bottom: 40px; font-size: 1.1rem;">
        A selection of our most critical infrastructure deployments. These environments require absolute precision, 
        where system failure translates to national-level consequences.
    </p>

    <div class="portfolio-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">

        <div class="portfolio-item" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--surface-border); border-radius: 12px; padding: 25px; transition: transform 0.3s ease, border-color 0.3s ease;">
            <div class="status-indicator" style="display: inline-block; padding: 4px 10px; background: rgba(0, 240, 255, 0.1); border: 1px solid var(--electric-blue); border-radius: 20px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--electric-blue); margin-bottom: 15px;">
                Status: Deployed
            </div>
            <h3 style="color: #fff; margin-bottom: 15px; font-family: var(--font-display); font-size: 1.2rem;">Project: OMEGA-WIND</h3>
            <h4 style="color: var(--text-muted); margin-bottom: 15px; font-weight: 300;">Offshore Turbine Telemetry</h4>
            <p style="font-size: 0.9rem; margin-bottom: 20px;">
                Engineered the SCADA integration for a 2.4 Gigawatt offshore wind array. 
                Implemented redundant, low-latency telemetry pipelines to ensure real-time blade pitch adjustment 
                during extreme meteorological events.
            </p>
        </div>

        <div class="portfolio-item" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--surface-border); border-radius: 12px; padding: 25px; transition: transform 0.3s ease, border-color 0.3s ease;">
            <div class="status-indicator" style="display: inline-block; padding: 4px 10px; background: rgba(0, 240, 255, 0.1); border: 1px solid var(--electric-blue); border-radius: 20px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--electric-blue); margin-bottom: 15px;">
                Status: Active Monitoring
            </div>
            <h3 style="color: #fff; margin-bottom: 15px; font-family: var(--font-display); font-size: 1.2rem;">Project: IRON-GRID</h3>
            <h4 style="color: var(--text-muted); margin-bottom: 15px; font-weight: 300;">Urban Sector Hardening</h4>
            <p style="font-size: 0.9rem; margin-bottom: 20px;">
                Overhaul of three primary distribution substations supplying a major metropolitan financial district. 
                Replaced legacy mechanical relays with solid-state, cryptographically verified automated defense systems.
            </p>
        </div>

        <div class="portfolio-item" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--surface-border); border-radius: 12px; padding: 25px; transition: transform 0.3s ease, border-color 0.3s ease;">
            <div class="status-indicator" style="display: inline-block; padding: 4px 10px; background: rgba(255, 170, 0, 0.1); border: 1px solid #ffaa00; border-radius: 20px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #ffaa00; margin-bottom: 15px;">
                Status: In Progress
            </div>
            <h3 style="color: #fff; margin-bottom: 15px; font-family: var(--font-display); font-size: 1.2rem;">Project: AEGIS</h3>
            <h4 style="color: var(--text-muted); margin-bottom: 15px; font-weight: 300;">Industrial Control Modernization</h4>
            <p style="font-size: 0.9rem; margin-bottom: 20px;">
                Currently executing a phase-three modernization of an automated manufacturing facility. 
                Establishing air-gapped data diodes and enforcing strict protocol validation on all internal logic controllers.
            </p>
        </div>

    </div>

    <style>
        .portfolio-item:hover {
            transform: translateY(-5px);
            border-color: var(--electric-blue);
            box-shadow: 0 10px 30px -10px rgba(0, 240, 255, 0.2);
        }
    </style>
</div>
