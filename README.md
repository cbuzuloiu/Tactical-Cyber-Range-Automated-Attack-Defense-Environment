## Overview

This project provisions a fully automated, standalone cybersecurity range using Infrastructure as Code (IaC) via Vagrant and Ansible. It is designed to act as a rigorous testing ground for both offensive operations (Red Team) and defensive hunting (Blue Team).

Rather than theoretical exercises, this range provides a brutal, realistic application of modern network topologies. It deploys a simulated public WAN interface facing an attacker, and an isolated private LAN where a target web server forwards high-fidelity telemetry to a dedicated SIEM.

If you want to train at an elite level, you need an environment that mirrors the real world. This is your arsenal.

## Infrastructure Architecture & Topology

The environment consists of three distinct virtual machines, logically separated into two network segments: a **Simulated WAN** and a **Private Monitoring LAN**.

|Hostname|Role|OS|Simulated WAN (eth1)|Private LAN (eth2)|
|---|---|---|---|---|
|**`kali-attacker`**|Offensive Node|Kali Linux Rolling|`81.196.12.50`|N/A|
|**`ubuntu-webserver`**|Target / Forwarder|Ubuntu 24.04|`81.196.12.100`|`10.10.10.10`|
|**`ubuntu-siem`**|Log Collector / Analytics|Ubuntu 24.04|N/A|`10.10.10.20`|

### Network Flow Logic

1. **The Attack Path:** The Kali Linux machine can only "see" the Ubuntu Web Server via the simulated public IP (`81.196.12.100`).
2. **The Defense Path:** The Web Server exists in a dual-homed state. Its network interfaces are set to promiscuous mode, allowing local security agents to sniff traffic from the WAN and silently forward telemetry via the private LAN (`10.10.10.0/24`) to the SIEM.

## Tactical Capabilities & Stack

The environment auto-deploys a pre-configured, battle-ready defensive stack on the target and collector nodes:

### 1. Endpoint Detection & Response (Sysmon for Linux)
- Deployed on the target web server using eBPF kernel dependencies.
- Initialized with a "Golden" XML filtering matrix that actively drops noisy baseline telemetry (like systemd and cron) while capturing critical anomalies.
### 2. Network Intrusion Detection (Suricata)
- Surgically bound to the WAN-facing interface (`eth1`).
- Loaded with custom local hunting rules (e.g., detecting Raw Layer 4 TCP Stealth Scans) to trigger immediate alerts upon reconnaissance phases.
### 3. Log Shipping & Analytics (Elastic Stack 8.x)
- **Filebeat:** Operates on the web server, ingesting Sysmon XML events from syslog, Suricata `eve.json` alerts, and Apache access/error logs.
- **Elasticsearch & Kibana:** Hosted entirely on the isolated `ubuntu-siem` node. Configured for high-performance memory mapping (`vm.max_map_count`) and exposed via plain HTTP for rapid access without wizard-setup friction.

## Deployment Protocol

### Prerequisites

To deploy this environment, your host machine must have the following installed:
- **VirtualBox** (Provider)
- **Vagrant** (Orchestrator)
- **Ansible** (Provisioner)
### Execution Steps

1. Clone the repository to your local machine:

```bash
git clone https://github.com/cbuzuloiu/Tactical-Cyber-Range-Automated-Attack-Defense-Environment.git

```

2. Trigger the automated build sequence. Vagrant will build the VMs, configure the static IPs natively via `nmcli`, and execute the Ansible playbooks to provision the software stack.
```shell
vagrant up
```
2. Once the deployment finishes, the environment is live.

## Access Points & Usage

### The SIEM Dashboard

Access Kibana from your host machine browser. Vagrant automatically forwards the port:
- **URL:** [http://127.0.0.1:5601](http://127.0.0.1:5601)
- _Note: X-Pack security is intentionally disabled for immediate lab access. No credentials are required._
### SSH Access

To enter any machine for localized testing or modification:

```
vagrant ssh kali
vagrant ssh webserver
vagrant ssh siem
```

### Strategic Scenarios to Execute
- **Reconnaissance Detection:** Run Nmap stealth scans from `kali` to the `webserver` (`81.196.12.100`) and observe Suricata alerts populating in Kibana.
- **Process Hunting:** Exploit a web vulnerability (or manually spawn a suspicious shell process on the web server) and track the execution tree via Sysmon telemetry in the SIEM.

> **Disclaimer:** This range is designed strictly for educational purposes, security research, and authorized penetration testing practice. Do not utilize these configurations or tools on environments where you do not have explicit authorization.

# Threat Model Scenario & Exploitation Lifecycle

## Executive Summary

This document outlines the multi-stage, high-consequence compromise scenario built into the **Apex Power Systems Cyber Range**. The range emulates a real-world targeted intrusion against an Industrial Control Systems (ICS) / SCADA grid management perimeter.

Rather than relying on unpatched kernel exploits, this scenario models **human engineering failure and architectural misconfigurations**. It showcases how minor operational oversights compound across the **MITRE ATT&CK** matrix to allow an external adversary to move from open-source intelligence gathering to full root-level control of a critical infrastructure asset.

```
[OSINT Scrape] ➔ [Username Enumeration] ➔ [Credential Spray] ➔ [RCE Upload] ➔ [Sudo Tar Abuse (Root)]
```

## The Exploitation Kill Chain

### Phase 1: Reconnaissance & Target Mapping

- **Objective:** Adversary intelligence gathering and employee profiling.
- **Mechanism:** The public-facing corporate facade contains an employee directory (`our_team.php`). This module serves as an intentional Open Source Intelligence (OSINT) leak, detailing real corporate names, structural roles, and standardized email formats (`<first_initial>.<lastname>@apexpwr.local`).
- **Adversary Action:** The attacker scrapes this roster to compile a clean, targeted dictionary of potential application usernames (e.g., mapping user Ryan Mercer to `r.mercer`).
### Phase 2: Initial Access via Logic Flaw (Information Disclosure)

- **Objective:** Bypassing the public perimeter to establish a session.
- **Target Endpoint:** `http://81.196.12.100/login.php`
- **Vulnerability Class:** Improper Error Handling / Verbose Authentication Responses (**CWE-204**).
- **The Flaw:** The server-side code handles data verification using explicit execution pathways that return distinct messages. An incorrect username triggers `Invalid operator ID.`, while a valid username with an incorrect password triggers `Incorrect authentication key.`.
- **Adversary Action:** Utilizing a network proxy (e.g., Burp Suite Intruder), the attacker runs a Sniper attack against the username field using the scraped team list. Because the response strings alter the final HTML layout size, the attacker filters by **Response Byte Length** to confirm which corporate accounts exist.
- **The Breach:** Once user account `r.mercer` is verified as active, a localized dictionary attack is launched against the password parameter, successfully guessing the weak operational credential: `password123`.
### Phase 3: Execution & Persistence (Remote Code Execution)

- **Objective:** Arbitrary command execution within the application container.
- **Target Endpoint:** `http://81.196.12.100/firmware_portal.php`
- **Vulnerability Class:** Unrestricted Upload of File with Dangerous Type (**CWE-434**).
- **The Flaw:** Once authenticated as a junior operator, the user gains access to an internal Substation Firmware Flash Utility. The PHP ingestion logic processes file transfers without performing file extension filtering, MIME-type validation, or magic-number verification. Uploaded artifacts are written directly into a web-accessible repository (`/uploads/`).
- **Adversary Action:** The attacker uploads a custom script configured with runtime system execution utilities. By sending an HTTP GET request directly to the uploaded path (`http://81.196.12.100/uploads/artifact.php`), the server parses the server-side code, establishing an interactive, inbound remote shell session running under the low-privileged local web context: `www-data`.
### Phase 4: Local Privilege Escalation (Living off the Land)

- **Objective:** Total compromise of the underlying Linux host system.
- **Target Infrastructure:** `/etc/sudoers.d/apex_web_diagnostics`
- **Vulnerability Class:** Principle of Least Privilege Violation / Sudo Misconfiguration.
- **The Flaw:** To allow the web application to package system diagnostic text logs owned by the root superuser, a lazy engineering adjustment granted the web runtime service profile (`www-data`) the right to invoke the native archiving utility (`/usr/bin/tar`) via `sudo` without password confirmation (`NOPASSWD`).
- **Adversary Action:** The attacker utilizes standard local enumeration techniques (such as executing automated configuration auditing tools like `LinPEAS`) to uncover the highly permissive sudo entry.
- **The Abuse:** By leveraging documented "Living off the Land" (LotL) execution parameters listed on references like **GTFOBins**, the attacker invokes `tar` with custom progress-tracking arguments (`--checkpoint=1 --checkpoint-action=exec=...`). Because `tar` is running with elevated root permissions via `sudo`, it evaluates the checkpoint command parameters natively, spawning a new shell context with absolute system privileges (`root`).
## The Purple Team Objective (SIEM Validation Matrix)

The true engineering goal of this scenario is to provide a reliable, predictable canvas for defensive alerting logic. When this complete attack path is executed, the following high-fidelity log trails are transmitted natively into your Elastic SIEM:

### Telemetry Footprints Captured:

|**Attack Phase**|**Ingestion Source**|**Telemetry Generated**|**Elastic KQL Hunting Query**|
|---|---|---|---|
|**Phase 2 (Brute Force)**|Apache Error Log (`filebeat-*`)|Structured application logging custom tags: `APEX_AUTH_FAIL`.|`message : "APEX_AUTH_FAIL"`|
|**Phase 3 (File Upload)**|Sysmon for Linux / Apache Logs|**Event ID 11 (FileCreate)** logged inside a known executable directory (`/var/www/html/uploads/`).|`file.path : *\/var\/www\/html\/uploads\/* AND file.extension : "php"`|
|**Phase 3 (Shell Spawn)**|Sysmon for Linux (`filebeat-*`)|**Event ID 1 (ProcessCreate)** showing an interactive terminal parented directly by the web server runtime binary.|`process.parent.executable : "/usr/sbin/apache2" AND process.executable : ("/bin/sh" OR "/bin/bash" OR "/bin/dash")`|
|**Phase 4 (PrivEsc)**|Sysmon for Linux (`filebeat-*`)|**Event ID 1 (ProcessCreate)** tracking an archiving utility (`tar`) spawning a shell while maintaining a root user ID context.|`process.parent.executable : "/usr/bin/tar" AND process.executable : ("/bin/sh" OR "/bin/bash")`|

> **Educational Note for Security Reviewers:** This attack lifecycle serves as an instructional framework to demonstrate the visibility gaps between network-level detection (which often misses internal logic flaws) and robust host-based behavioral logging (which unfailingly catches process anomalies).
