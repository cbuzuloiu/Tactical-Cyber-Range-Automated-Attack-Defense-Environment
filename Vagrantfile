# -*- mode: ruby -*-
# vim: ft=ruby :

Vagrant.configure("2") do |config|

  # Global VirtualBox Provider Configurations
  config.vm.provider "virtualbox" do |vb|
    vb.gui = false # Keep it headless for performance; change to true if you want the GUI screen popup
    vb.customize ["modifyvm", :id, "--ioapic", "on"]
  end

  # =========================================================================
  # 1. KALI ATTACKER MACHINE
  # =========================================================================
  config.vm.define "kali" do |kali|
    kali.vm.box = "kalilinux/rolling"
    kali.vm.hostname = "kali-attacker"

    # Adapter 2: Simulated Internet WAN
    kali.vm.network "private_network", 
      ip: "192.0.2.50",
      netmask: "255.255.255.0",
      virtualbox__intnet: "intnet_wan"

    kali.vm.provider "virtualbox" do |vb|
      vb.name = "V2_Kali_Attacker"
      vb.memory = 4096
      vb.cpus = 2
    end
    # --- INLINE IAC PROVISIONER ---
    # This script fires automatically on the very first boot sequence
    kali.vm.provision "shell", inline: <<-SHELL
      echo "[IaC] Automating Kali WAN Interface Configuration (eth1)..."
      
      # 1. Check if the NetworkManager profile already exists to prevent duplication
      if ! nmcli connection show "WAN-Static" >/dev/null 2>&1; then
        # 2. Bind the static IP to the eth1 card natively inside the automation loop
        nmcli connection add type ethernet con-name WAN-Static ifname eth1 ip4 192.0.2.50/24
        nmcli connection modify WAN-Static ipv4.never-default yes
        nmcli connection up WAN-Static
        echo "[IaC] Interface eth1 successfully locked onto 192.0.2.50/24"
      else
        echo "[IaC] WAN-Static configuration already active. Skipping setup."
      fi
    SHELL
  end

  # =========================================================================
  # 2. UBUNTU TARGET WEBSERVER
  # =========================================================================
  config.vm.define "webserver" do |web|
    web.vm.box = "bento/ubuntu-24.04"
    web.vm.hostname = "ubuntu-webserver"

    # Adapter 2: Simulated Internet WAN (Facing Kali)
    web.vm.network "private_network", 
      ip: "192.0.2.100",
      netmask: "255.255.255.0",
      virtualbox__intnet: "intnet_wan"

    # Adapter 3: Private Monitoring LAN (Facing SIEM)
    web.vm.network "private_network", 
      ip: "10.10.10.10", 
      netmask: "255.255.255.0",
      virtualbox__intnet: "intnet_lan"

    web.vm.provider "virtualbox" do |vb|
      vb.name = "V2_Ubuntu_Webserver"
      vb.memory = 2048
      vb.cpus = 2
      # Open promiscuous mode so Suricata can sniff Adapter 2 and 3 seamlessly
      vb.customize ["modifyvm", :id, "--nicpromisc2", "allow-all"]
      vb.customize ["modifyvm", :id, "--nicpromisc3", "allow-all"]
    end
    # --- AUTOMATED ANSIBLE PROVISIONER ---
    # Vagrant will automatically trigger this playbook from your Fedora host
    web.vm.provision "ansible" do |ansible|
      ansible.inventory_path = "ansible/inventory.ini"
      ansible.playbook = "ansible/playbook-webserver.yml"
      ansible.compatibility_mode = "2.0"
    end
  end

  # =========================================================================
  # 3. UBUNTU SIEM COLLECTOR
  # =========================================================================
  config.vm.define "siem" do |siem|
    siem.vm.box = "bento/ubuntu-24.04"
    siem.vm.hostname = "ubuntu-siem"

    # Adapter 2: Private Monitoring LAN (Facing Webserver Only)
    siem.vm.network "private_network", 
      ip: "10.10.10.20", 
      netmask: "255.255.255.0",
      virtualbox__intnet: "intnet_lan"
    
    # --- ACCESS SIEM AT http://127.0.0.1:5601 ---
    siem.vm.network "forwarded_port", guest: 5601, host: 5601

    siem.vm.provider "virtualbox" do |vb|
      vb.name = "V2_Ubuntu_SIEM"
      vb.memory = 4096 # Kept at 4GB for baseline setup; we can scale up later
      vb.cpus = 2
    end
    # --- AUTOMATED ANSIBLE PROVISIONER ---
    # Vagrant will automatically trigger this playbook from your Fedora host
    siem.vm.provision "ansible" do |ansible|
      ansible.inventory_path = "ansible/inventory.ini"
      ansible.playbook = "ansible/playbook-siem.yml"
      ansible.compatibility_mode = "2.0"
    end

  end

end
