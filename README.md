# Tactical Cyber Range Automated Attack And Defense Environment

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
