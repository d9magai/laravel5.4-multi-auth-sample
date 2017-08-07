#!/bin/bash
set -eux
cd `dirname $0`

if [ -n "$(command -v yum)" ]; then
    sudo yum install -y epel-release
    sudo rpm -ivh --replacepkgs https://rpms.remirepo.net/enterprise/remi-release-7.rpm
    sudo yum install -y --enablerepo=remi-php71 vim git nkf colordiff docker httpd zsh php php-cli php-mbstring php-xml php-pecl-zip php-pdo php-mysqlnd php-pecl-xdebug
    sudo systemctl enable docker
    sudo systemctl start docker
    sudo systemctl enable httpd
    sudo systemctl start httpd
elif [ -n "$(command -v apt-get)" ]; then
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    sudo apt-get update >/dev/null
    sudo apt-get install -y git zsh vim nkf colordiff docker-ce apache2 mysql-client php php-curl php-zip php-cli php-mbstring php-xml php-mysql php-xdebug
fi

if type "composer" > /dev/null 2>&1; then
    composer self-update
else
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    composer config -g repositories.packagist composer https://packagist.jp
    composer global require hirak/prestissimo
fi

sudo docker run -p 3306:3306 --restart=always --name mysqld -e MYSQL_USER=homestead -e MYSQL_PASSWORD=secret -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=homestead -d mysql
composer install
cp .env.example .env
php artisan key:generate
php artisan config:clear

