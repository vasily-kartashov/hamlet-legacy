Vagrant.configure("2") do |config|
  config.vm.box = "debian-wheezy72-x64-vbox43"
  config.vm.box_url = "https://puphpet.s3.amazonaws.com/debian-wheezy72-x64-vbox43.box"

  # ensure this is unique for each project, so multiple VMs can be run
  config.vm.network "private_network", ip: "192.168.66.101"
  # port forwarding if required
  #config.vm.network :forwarded_port, host: 8080, guest: 80

  # currently just using the default /vagrant/ folder which is this directory
  #config.vm.synced_folder "../", "/var/www", id: "vagrant-root", :nfs => false

  config.vm.usable_port_range = (2200..2250)
  config.vm.provider :virtualbox do |virtualbox|
    virtualbox.customize ["modifyvm", :id, "--name", "foundation"]
    virtualbox.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    virtualbox.customize ["modifyvm", :id, "--memory", "512"]
    virtualbox.customize ["setextradata", :id, "--VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
  end

  config.vm.provision :shell, :path => "Vagrant/shell/initial-setup.sh"
  config.vm.provision :shell, :path => "Vagrant/shell/update-puppet.sh"
  config.vm.provision :shell, :path => "Vagrant/shell/librarian-puppet-vagrant.sh"
  config.vm.provision :puppet do |puppet|
    puppet.facter = {
      "ssh_username" => "vagrant"
    }

    puppet.manifests_path = "Vagrant/puppet/manifests"
    puppet.options = ["--verbose", "--hiera_config /vagrant/Vagrant/hiera.yaml", "--parser future"]
  end

  config.ssh.username = "vagrant"

  config.ssh.shell = "bash -l"

  config.ssh.keep_alive = true
  config.ssh.forward_agent = false
  config.ssh.forward_x11 = false
  config.vagrant.host = :detect
end

