# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
locale-gen

apt-get update -y
apt-get install -y apt-transport-https
apt-key adv --keyserver hkp://pgp.mit.edu:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D

echo "deb https://apt.dockerproject.org/repo debian-jessie main" > /etc/apt/sources.list.d/docker.list

apt-get update -y
apt-get install -y docker-engine

usermod -G docker vagrant

apt-get install -y php5-cli php5-json

curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
SCRIPT

Vagrant.configure(2) do |config|
    config.vm.box = "debian/jessie64"
    config.vm.provision 'shell', inline: $script
end