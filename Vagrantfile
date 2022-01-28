# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "ubuntu/xenial64"
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.hostname = "dev.local"
    config.vm.provider "virtualbox" do |v|
        v.memory = 1024
        v.cpus = 1
    end
    config.vm.synced_folder "./www", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "./mysql", "/var/lib/mysql", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "./priv", "/home/vagrant/priv", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.provision "shell", path: "bootstrap.sh"
    config.vm.provision "shell", :run => 'always', inline: <<-SHELL
        
        echo -e "\n--- Avvia MySQL ---\n"
        sudo /etc/init.d/mysql start
        
    SHELL

end
