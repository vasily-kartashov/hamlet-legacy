#!/bin/bash

sudo apt-get update

sudo apt-get -y install apache2 php5 php5-cli php-pear php5-mysql php5-curl unzip make php5-dev libpcre3-dev memcached php5-memcached php5-sqlite jpegtran -y
sudo pecl install xdebug
printf "\n" | sudo pecl install apc
sudo apt-get install php5-gd -y

sudo cp /vagrant/provision/config/dev /etc/apache2/sites-available
sudo cp /vagrant/provision/config/php.ini /etc/php5/apache2
sudo cp /vagrant/provision/config/php.ini /etc/php5/cli
sudo cp /vagrant/provision/config/apc.ini /etc/php5/apache2/conf.d
sudo cp /vagrant/provision/config/xdebug.ini /etc/php5/apache2/conf.d
sudo cp /vagrant/provision/config/hosts /etc

php /vagrant/desktop/utils/create-library-loader.php
php /vagrant/desktop/utils/google-drive-import.php
php /vagrant/mobile/utils/create-library-loader.php
php /vagrant/mobile/utils/google-drive-import.php

sudo a2dismod negotiation
sudo a2dissite default
sudo a2ensite dev
sudo a2enmod rewrite
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod ssl
sudo service apache2 restart