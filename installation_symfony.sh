#!/bin/bash

#installation de curl

sudo apt install curl

# installation de git

sudo apt install git-all

# Installation de PHP 8.3

sudo apt update
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3 php8.3-xml php8.3-mysql

# Installation de Composer

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer

# Installation de Symfony CLI
curl -sS https://get.symfony.com/cli/installer | bash
export PATH="$HOME/.symfony/bin:$PATH"
echo 'export PATH="$HOME/.symfony/bin:$PATH"' >> ~/.bashrc
sudo mv ~/.symfony5/bin/symfony /usr/local/bin/symfony

echo "Installation terminée. Assurez-vous de recharger la session ou ouvrez un nouveau terminal pour utiliser les nouvelles commandes."
