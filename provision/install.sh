#!/bin/bash

sudo apt-get update
sudo apt-get install python-software-properties -y
sudo add-apt-repository ppa:ondrej/php5 -y
sudo apt-get update

sudo apt-get install apache2 php5 php5-cli php-pear php5-mysql php5-curl unzip make php5-dev php-apc php5-xdebug \
             libpcre3-dev memcached php5-memcached php5-sqlite libjpeg-progs libapache2-mod-php5 -y

sudo cp /vagrant/provision/config/dev.conf /etc/apache2/sites-available
sudo cp /vagrant/provision/config/servername.conf /etc/apache2/conf-available
sudo cp /vagrant/provision/config/php.ini /etc/php5/apache2
sudo cp /vagrant/provision/config/php.ini /etc/php5/cli
sudo cp /vagrant/provision/config/xdebug.ini /etc/php5/apache2/conf.d
sudo cp /vagrant/provision/config/hosts /etc

php /vagrant/utils/create-library-loader/create-library-loader.php

sudo a2enconf servername
sudo a2dismod negotiation
sudo a2dissite 000-default
sudo a2ensite dev
sudo a2enmod rewrite
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod ssl
sudo service apache2 restart