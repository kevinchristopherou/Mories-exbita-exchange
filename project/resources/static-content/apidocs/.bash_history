cd ..
sudo apt update 
sudo apt install -y nginx 
http://server-ip
sudo apt install -y php7.4-{mysql,redis,curl,bcmath,json,zip,intl,mbstring,gd,xml}
sudo apt install -y composer
sudo apt install -y redis-server
sudo service redis-server status
sudo apt install -y supervisor
sudo nano /etc/php/7.4/fpm/php.ini
sudo chown -R root:www-data /var/www/html
sudo find /var/www/html -type f -exec chmod 664 {} \;
sudo find /var/www/html -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache
sudo chmod -R ug+rwx /var/www/www-root/data/www/ktc.exchage/storage /var/www/html/bootstrap/cache
sudo chmod -R ug+rwx /var/www/www-root/data/www/ktc.exchange/storage /var/www/html/bootstrap/cache
nano /etc/nginx/sites-available/default
sudo systemctl restart nginx
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_http_module modules/mod_proxy_http.so
cd var
cd www
cd html
cd ktc.exchange
composer install
npm install
npm run production
apt install npm
cd ..
cd 
apt install npm
cd 
cd ..
cd var
cd www
cd html
composer install
npm install
npm run production
cd ‥
cd ..
cd www-root
cd data
cd www
cd ktc.exchange
composer install
npm install
npm run production
php artisan key:generate
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan config:cache
php artisan exbita:create-admin
composer install
npm install
npm run production
cd ..
cd var
cd www
cd www-root
cd ktc.exchange
cd www
cd html
cd data
cd www
cd ktc.exchange
sudo chown -R root:www-data /var/www/www-root/data/www/ktc.exchang
sudo find /var/www/www-root/data/www/ktc.exchange -type f -exec chmod 664 {} \;
sudo find /var/www/www-root/data/www/ktc.exchange -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx //var/www/www-root/data/www/ktc.exchange/bootstrap/cache
sudo chown -R root:www-data /var/www/www-root/data/www/ktc.exchange
sudo find /var/www/www-root/data/www/ktc.exchange -type f -exec chmod 664 {} \;
sudo find /var/www/www-root/data/www/ktc.exchange -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx //var/www/www-root/data/www/ktc.exchange/bootstrap/cache
exit
ls
df -h
cd /var/www/httpd-logs/
cd ..
cd html/
ls
cd ..
ls
cd www/
ls
cd www-root/
ls
cd data/
ls
cd www/
ls
cd ktc.exchange/
ls
sudo nano /etc/nginx/sites-available/default 
sudo nano /etc/nginx/vhosts/www-root/ktc.exchange.conf 
sudo nginx -t
sudo service nginx restart
pwd
sudo nano /etc/nginx/vhosts/www-root/ktc.exchange.conf 
php /var/www/www-root/data/www/ktc.exchange/artisan
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
sudo chmod -R 777 storage/
supervisorctl status
crontab 
crontab -e
exit
sudo laravel-echo-server configure
nginx -t
sudo systemctl restart nginx
sudo npm install pm2@latest -g
pm2 startup
sudo pm2 start /var/www/www-root/data/www/ktc.exchange/echo-server/laravel-echo-server-pm2.json
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
supervisorctl status
crontab -e
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt install libssl-dev
sudo apt install libboost-all-dev
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt install libssl-dev
sudo apt install libboost-all-dev
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt install libssl-dev
sudo apt install libboost-all-dev
sudo apt update
sudo apt install build-essential
sudo apt install libtool autotools-dev autoconf
sudo apt install libssl-dev
sudo apt install libboost-all-dev
sudo apt install php7.4
sudo apt install php7.4-curl
cd ~
wget https://bitcoincore.org/bin/bitcoin-core-0.19.1/bitcoin-0.19.1-x86_64-linux-gnu.tar.gz
tar xvzf bitcoin-0.19.1-x86_64-linux-gnu.tar.gz
sudo ln -s ~/bitcoin-0.19.1/bin/bitcoind /usr/bin/bitcoind
sudo ln -s ~/bitcoin-0.19.1/bin/bitcoind-cli /usr/bin/bitcoind-cli
mkdir ~/.bitcoin 
cd ~/.bitcoin/
touch bitcoin.conf
nano bitcoin.conf
btc
cd btc
cd .
cd ..
cd /var/www/www-root/data/www/ktc.exchange
cd scripts
cd btc
# replace mysername mypassword strings with your real username and password
python3 rpcauth.py myusername mypassword
cd ..
cd bitcoin
cd bitcoind
cd bitcoin№
cd bitcoin#
cd ..
cd bitcoin
cd ..
cd bitcoin
cd ~ bitcoin
~ cd bitcoin
cd ~/.bitcoin/
nano bitcoin.conf
bitcoind
nano bitcoin.conf
bitcoind
nano bitcoin.conf
bitcoind
bitocind
bitcoind
bitcoin-cli
bitcoin-cli stop
bitcoind stop
bitcoind -h
bitcoin-cli stop
bitcoind
cd .bitcoin.
cd .bitcoin/
bitcoin-cli stop
bitcoind stop
bitcoind -h
bitcoind -server
bitcoin-cli validateaddress 
bitcoin-cli validateaddress
bitcoin-cli balance
bitcoin-cli getblockcount
bitcoin-cli getblock
bitcoin-cli getbalance
bitcoind
bitcoind stop
bitcoind start
bitcoind -stop
bitcoind -start
bitcoin-cli getblockchaininfo
netstat -tulnp | grep bitcoind
cd ..
apt install net-tools
netstat -tulnp | grep bitcoind
pkill -9 -f bitcoind
bitcoind
bitcoin-cli getinfo
cd /root/bitcoin-0.19.1/bin
./bitcoin-cli getblockchaininfo
./bitcoin-cli getmininginfo
bitcoin-cli getmininginfo
./bitcoin-cli getmininginfo
crontab -e
bitcoin-cli -rpcconnect="185.238.169.171" -rpcport="18332" -rpcuser="myusername" -rpcpassword="479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba" getblockchaininfo
./bitcoin-cli -rpcconnect="185.238.169.171" -rpcport="18332" -rpcuser="myusername" -rpcpassword="479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba" getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=479548a2e36898ef081d8aa68f9949a0 -rpcpassword=9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
pm2 log
pm2 list
pm2 log
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=479548a2e36898ef081d8aa68f9949a0 -rpcpassword=9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=479548a2e36898ef081d8aa68f9949a0 -rpcpassword=$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=:479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
crontab -e
pidof  bitcoind
kill 419882
kill -9 419882
bitcoind -daemon
bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
./bitcoin-cli -rpcconnect=185.238.169.171 -rpcport=18332 -rpcuser=myusername -rpcpassword=479548a2e36898ef081d8aa68f9949a0$9f06ee3816c14070e71a0b080ed788776c15b6e30a004a1aa317ebe871b280ba getblockchaininfo
pidof  bitcoind
kill 447890
kill -9 447890
cd
bitcoind -daemon
cd /root/bitcoin-0.19.1/bin
cp bitcoin-cli /usr/local/bin
cd
bitcoin-cli getmininginfo
pidof bitcoind
kill 447999
kill -9 447999
bitcoind -daemon
pidof  bitcoind
kill 448097
bitcoind -daemon
bitcoin-cli getmininginfo
pidof bitcoind
kill 448226
bitcoind -daemon
bitcoin-cli getmininginfo
pidof bitcoind
kill 448477
bitcoind -daemon
bitcoin-cli getmininginfo
pidof bitcoind
kill 448632
bitcoind -daemon
bitcoin-cli getblockchaininfo
pidof bitcoind
kill 448733
bitcoind -daemon
pm2 log
bitcoin-cli stop
bitcoin-cli getblockchaininfo
bitcoin-cli getblbnce
bitcoin-cli getbalance
bitcoin-cli getmininginfo
bitcoin-cli getblockchaininfo
sudo nano /etc/nginx/snippets/phpmyadmin.conf
include snippets/phpmyadmin.conf;
sudo nano /etc/nginx/snippets/phpmyadmin.conf
sudo nginx -t
sudo nano /etc/nginx/snippets/phpmyadmin.conf
sudo nginx -t
sudo rm -rf /etc/nginx/sites-enabled/default
sudo systemctl restart nginx
sudo systemctl status nginx
sudo nano /etc/nginx/snippets/phpmyadmin.conf
sudo nginx -t
sudo systemctl status nginx
sudo nano /etc/nginx/snippets/phpmyadmin.conf
sudo systemctl status nginx
sudo systemctl restart nginx
sudo systemctl status nginx
sudo nginx -t
sudo nano /etc/nginx/snippets/phpmyadmin.conf
pm2 log
pm2 list
pm2 log
crontab-e
service nginx restart
nginx -s reload
nginx -t
service nginx restart
x.service" and "journalctl -xe" for details.
root@v248180234:~#
systemctl restart nginx
systemctl start nginx
systemctl enable nginx
systemctl start nginx
systemctl restart nginx
tail -f /var/log/nginx/error.log
ps auxf | grep nginx
chown -R www-data:www-data /var/www/ktc.exchange
netstat -plant | grep '80\|443'
ufw status
systemctl restart nginx
sudo /etc/init.d/apache2 stop
sudo systemctl restart nginx
$ service nginx configtest
systemctl stop apache2
systemctl status apache2
systemctl stop nginx
systemctl status nginx
sudo nano /etc/nginx/sites-available/default
udo systemctl restart nginx
ssudo systemctl restart nginx
sudo systemctl restart nginx
sudo apt-get install nginx
NGINX Server не запускается [Закрыто]
/etc/init.d/nginx restart 
sudo killall apache2
/etc/init.d/nginx restart 
sudo netstat -lntp | grep ":80"
sudo -i
systemctl check nginx
systemctl disable nginx
systemctl enable nginx
systemctl start nginx
sudo nginx -s reload
sudo systemctl restart nginx
sudo pkill -9 nginx && sudo systemctl start nginx
sudo netstat -ant | grep ": 80"
sudo lsof -i -P -n | grep ": 80"
sudo systemctl start nginx
journalctl -xe
/etc/nginx/sites-enabled$ cat default
nginx -t -c /etc/nginx/nginx.conf
systemctl status nginx
systemctl start nginx
systemctl enable nginx
systemctl start nginx
systemctl restart nginx
ps auxf | grep nginx
netstat -plant | grep '80\|443'
ufw status
sudo nano /etc/default/ufw
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw enable
ufw status
systemctl restart nginx
service nginx restart
ghnhgn
sudo ufw disable
service nginx restart
sudo ln -s /etc/nginx/sites-available/potree.local.conf /etc/nginx/sites-enabled
sudo service nginx restart
pm2 log
sudo nano /etc/nginx/sites-available/ktc.exchange
sudo nginx -t
sudo systemctl reload nginx
sudo service nginx restart
sudo service apache2 stop
sudo apt-get purge apache2
sudo service apache2 stop
sudo apt-get purge apache2
sudo apt-get update
sudo apt-get install nginx
sudo service nginx restart
sudo systemctl status nginx.service
sudo systemctl reload nginx
sudo service nginx start
sudo apt update && sudo apt -y upgrade
sudo service nginx start
sudo systemctl restart nginx.service
sudo journalctl -xel
sudo reboot
$ sudo systemctl restart nginx.service
$ sudo nginx -t
sudo apt install nginx
sudo ufw app list
systemctl status nginx
sudo systemctl stop nginx
sudo systemctl start nginx
sudo systemctl restart nginx
sudo systemctl reload nginx
sudo systemctl disable nginx
sudo systemctl enable nginx
systemctl status nginx
sudo service nginx restart
systemctl stop nginx
systemctl status nginx
systemctl start nginx
sudo ln -s /etc/nginx/sites-available/example.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo ln -s /etc/nginx/sites-available/ktc.exchange /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
sudo nginx -t
cd /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/ktc.exchange /etc/nginx/sites-enabled/
sudo nginx -t
nginx -t
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
nginx -t
sudo systemctl restart nginx
include /etc/nginx/templates/phpmyadmin.conf;
service nginx restart
nginx -t
service nginx restart
include snippets/phpmyadmin.conf;
nginx -t
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
sudo systemctl restart nginx
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www/ktc.exchange
cd
sudo apt-get install phpmyadmin php-mbstring php-gettext
sudo phpenmod mcrypt
sudo apt install php7.4-mcrypt
sudo ln -s /etc/php/7.2/mods-available/mcrypt.ini /etc/php/7.4/mods-available/
sudo phpenmod mcrypt
sudo ln -s /etc/php/7.4/mods-available/mcrypt.ini /etc/php/7.4/mods-available/
sudo service php7.4-fpm restart
sudo phpenmod mcrypt
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo phpenmod mcrypt
sudo apt-add-repository ppa:ondrej/php
sudo apt-get install php7.4-mbstring
sudo apt-get install php7.3-mbstring
sudo apt-get install php7.2-mbstring
sudo apt-get install php7.1-mbstring
sudo apt-get install php7.1-mcrypt
sudo ln -s /etc/php/7.1/mods-available/mcrypt.ini /etc/php/7.4/mods-available/
sudo phpenmod mcrypt
sudo ln -s /etc/php/7.1/mods-available/mcrypt.ini /etc/php/7.4/mods-available/
sudo phpenmod mcrypt
sudo apt-get install php7.4-mbstring
sudo apt-get install php7.1-mbstring
sudo apt-get install php7.3-mbstring
sudo apt-get install php7.2-mbstring
sudo phpenmod mbstring
sudo phpenmod mcrypt
sudo apt-get install php7.1-mbstring
sudo apt-get install php7.1-mcrypt
sudo apt-get -y install gcc make autoconf libc-dev pkg-config
sudo apt-get -y install libmcrypt-dev
sudo pecl install mcrypt-1.0.3
extension=/path_to_mcrypt/mcrypt.so
extension=//etc/php/7.4/mods-available/mcrypt.so
sudo phpenmod mbstring
sudo phpenmod mcrypt
sudo vim /etc/php/7.4/cli/php.ini
sudo pecl update-channels
sudo pecl install mcrypt
sudo apt update
sudo apt install -y build-essential
sudo apt install php php-pear php-dev libmcrypt-dev
which pecl 
sudo pecl update-channels
sudo pecl search mcrypt
sudo pecl install mcrypt
sudo vim /etc/php/7.4/cli/php.ini
sudo vim /etc/php/7.4/apache2/php.ini
extension=mcrypt.so
sudo vim /etc/php/7.4/apache2/php.ini
php -m | grep mcrypt
mcrypt
apt install mcrypt
sudo systemctl restart apache2
sudo systemctl restart nginx
sudo phpenmod mcrypt
sudo phpenmod mbstring
sudo phpenmod mcrypt
sudo vim /etc/php/7.4/cli/php.ini
sudo nano /etc/php/7.4/cli/php.ini
mcrypt
sudo systemctl restart nginx
sudo systemctl restart apache2
sudo phpenmod mcrypt
pm2 list
sudo phpenmod mbstring
sudo mysql
mysql -u root -p
sudo /etc/init.d/mysql stop
sudo mysqld_safe --skip-grant-tables &
mysql -uroot
use mysql;
mysql -u root
sudo /etc/init.d/mysql start
sudo mysqld_safe --skip-grant-tables &
use mysql;
mysql -uroot
mysql -u root
udo mysql
sudo mysql
mysql -u root -p 7Gsudo mysqlgMisudo mysqlBkMpsudo mysqld
sudo mysql
mysql -u root -p
sudo ln -s /usr/share/phpmyadmin/ /var/www/html/phpmyadmin
ls -al /var/www/html
sudo php5enmod mcrypt
sudo service php7.0-fpm restart
sudo service php5-fpm
sudo service php7.4-fpm
sudo php7.4enmod mcrypt
sudo php7enmod mcrypt
sudo nano /etc/apache2/apache2.conf
/etc/init.d/apache2 restart
service apache2 restart
sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin
ip addr show eth0 | grep inet | awk '{ print $2; }' | sed 's/\/.*$//'
sudo apt update && sudo apt install phpmyadmin
$ sudo systemctl status nginx 
systemctl status nginx
systemctl is-enabled nginx
sudo systemctl status php7.4-fpm
sudo systemctl is-enabled php7.4-fpm
sudo chmod 775 -R /usr/share/phpmyadmin/
sudo chown root:www-data -R /usr/share/phpmyadmin/
sudo systemctl restart nginx
sudo chmod 775 -R /usr/share/phpmyadmin/
sudo chown root:www-data -R /usr/share/phpmyadmin/
sudo apt install phpmyadmin
sudo ln -s /usr/share/phpmyadmin /var/www/
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
sudo /etc/init.d/mysql start
sudo /etc/init.d/mysql restart
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
sudo ln -s /usr/share/phpmyadmin /var/www/
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
sudo /etc/init.d/mysql restart
sudo apt-get purge phpmyadmin
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
sudo apt install phpmyadmin
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
ps aux | grep -i apt
sudo kill -9 149616
sudo kill -9 149617
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
sudo apt-get install -f && sudo dpkg --configure -a
sudo DEBCONF_DEBUG=developer apt-get install -f
sudo apt-get update && sudo apt-get -y upgrade && sudo apt-get -y dist-upgrade
sudo reboot
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo mysql
mysql -u root -p
sudo phpenmod mcrypt
mysql -u root -p
sudo apt install phpmyadmin
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo apt-get purge phpmyadmin
sudo apt update
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo mysql
mysql -u root -p
sudo ln -s /usr/share/phpmyadmin /var/www/
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
sudo apt-get remove nginx*
sudo apt-get purge nginx*
sudo apt install nginx
sudo ufw app list
systemctl status nginx
nano /etc/nginx/sites-available/default
ginx -t
nginx -t
sudo systemctl restart nginx
APACHE_PATH\conf\extra\httpd-vhosts.conf
pm2 list
pm2 log
sudo apt-get purge apache2 apache2-utils apache2.2-bin apache2-common
sudo apt-get autoremove
whereis apache2
sudo rm -Rf /etc/apache2
sudo apt install apache2
sudo ufw app list
sudo apt install apache2
sudo apt update
apt list --upgradable
sudo apt install apache2
sudo ufw allow 'Apache'
sudo ufw status
sudo systemctl status apache2
sudo apt install apache2
sudo systemctl status apache2
sudo apt install apache2
sudo systemctl is-enabled apache2.service
sudo apt install -y nginx 
sudo apt install -y php7.4-fpm
sudo apt install -y php7.4-{mysql,redis,curl,bcmath,json,zip,intl,mbstring,gd,xml}
sudo apt install -y composer
sudo systemctl restart nginx
sudo apt-get remove nginx*
sudo apt-get purge nginx*
sudo apt update 
sudo apt install -y nginx 
sudo apt install -y php7.4-fpm
sudo apt install -y php7.4-{mysql,redis,curl,bcmath,json,zip,intl,mbstring,gd,xml}
sudo apt install apache2
sudo ufw app list
sudo ufw allow 'Apache'
sudo ufw status
sudo systemctl status apache2
sudo apt install apache2
sudo apt update
apt list --upgradable
sudo apt-get purge apache2 apache2-utils apache2.2-bin apache2-common
whereis apache2
sudo rm -Rf /etc/apache2
nginx -t
sudo systemctl restart nginx
nginx -t
sudo systemctl restart nginx
sudo chown -R root:www-data /var/www/html
sudo find /var/www/html -type f -exec chmod 664 {} \;
sudo find /var/www/html -type d -exec chmod 775 {} \;
sudo chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache
pm
pm2 restart
pm2 log
pm2 
list
pm2 list
pm2 restart echoserver
pm2 log
sudo apt update 
sudo apt install -y php7.4-fpm
apt list --upgradable
linux-generic/focal-updates
sudo apt  upgrade
sudo apt update 
sudo apt install -y nginx 
sudo apt install -y php7.4-fpm
sudo apt install -y php7.4-{mysql,redis,curl,bcmath,json,zip,intl,mbstring,gd,xml}
sudo apt install -y composer
sudo /etc/init.d/php7.4-fpm restart
sudo systemctl restart nginx
chown -R phpma:phpma /var/lib/phpmyadmin
chown -R phpma:phpma /etc/phpmyadmin
chown -R phpma:phpma /usr/share/phpmyadmin
sudo dpkg-reconfigure -plow phpmyadmin
systemctl status nginx
sudo apt-get remove nginx*
sudo apt-get purge nginx*
sudo service nginx stop
sudo apt remove nginx
sudo apt purge nginx
sudo apt autoremove
sudo apt install -y nginx 
sudo apt install -y php7.4-fpm
sudo apt install -y php7.4-{mysql,redis,curl,bcmath,json,zip,intl,mbstring,gd,xml}
sudo systemctl restart nginx
sudo apt install nginx
sudo apt autoremove
sudo apt install nginx
sudo systemctl restart nginx
sudo netstat -plant | grep 80
sudo systemctl stop apache2
sudo systemctl start nginx
sudo netstat -plant | grep 80
sudo systemctl stop apache2
sudo systemctl start nginx
sudo fuser -k 80/tcp
service nginx start
pm2 log
pm2 list
sudo systemctl restart nginx
LoadModule rewrite_module modules/mod_rewrite.so
find /etc -name httpd.conf
Include /etc/apache2/httpd.conf
cd /etc/apache2
grep -C 1 httpd.conf apache2.conf
APACHE_PATH\conf\extra\httpd-vhosts.conf
cd
APACHE_PATH\conf\extra\httpd-vhosts.conf
sudo systemctl status apache2
sudo apache2ctl configtest
sudo apt-get remove --purge apache2 apache2-data apache2-utils
sudo apt-get install apache2
sudo systemctl restart nginx
sudo ufw status
sudo systemctl is-enabled apache2.service
sudo systemctl enable apache2.service
sudo systemctl start apache2.service
sudo systemctl stop apache2.service
sudo systemctl restart apache2.service
sudo systemctl reload apache2.service
sudo systemctl status apache2.service
apachectl stop
/etc/init.d/apache2 start
/etc/init.d/apache2 reload
sudo systemctl restart apache2
sudo systemctl restart httpd
sudo yum install httpd
sudo apt install httpd
sudo systemctl status httpd
sudo systemctl status apache2
sudo ps aux | grep -E 'apache2|httpd'
sudo apache2ctl -t
sudo httpd -t
sudo ufw status
sudo ufw allow 'Apache'
sudo ufw status
sudo ufw app list
sudo apt install apache2
sudo apt autoremove
sudo ufw status
sudo systemctl status apache2
sudo systemctl stop  nginx
sudo systemctl start apache2.service
sudo systemctl restart apache2.service
systemctl start nginx
sudo systemctl restart nginx
sudo apt install phpmyadmin
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo systemctl stop apache2.service
sudo systemctl start apache2.service
sudo systemctl enable apache2.service
sudo apt install phpmyadmin
sudo apt-get purge phpmyadmin
sudo apt-get install phpmyadmin
sudo dpkg-reconfigure phpmyadmin
sudo systemctl status apache2
sudo systemctl status nginx
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
cd /var/www/www-root/data/www
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www
sudo ln -s /usr/share/phpmyadmin /var/www/html
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www/ktc.exchange/public
cd
bitcoin-cli getblockchaininfo
bitcoind -daemon
sudo pm2 start /var/www/html/echo-server/laravel-echo-server-pm2.json
sudo laravel-echo-server configure
sudo npm install pm2@latest -g
pm2 startup
sudo pm2 start /var/www/html/echo-server/laravel-echo-server-pm2.json
pm2 list
laravel-echo-server start
cd /var/www/html
sudo laravel-echo-server configure
nginx -t
sudo systemctl restart nginx
pm2 list
playcoind -daemon
pm2 log
reboot
sudo systemctl restart nginx
bitcoind -daemon
playcoind -daemon
sudo service apache2 restart
pm2 list
sudo nginx -t
sudo systemctl restart nginx
sudo service php7.0-fpm status
sudo systemctl status php7.0-fpm
sudo service php7.0-fpm restart
sudo service php7.4-fpm status
sudo service php7.4-fpm restart
echo $?
sudo ufw status
service apache2 restart
sudo systemctl reload apache2
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo apt install phpmyadmin php-mbstring php-zip php-gd php-json php-curl
mysql -u root -p
sudo phpenmod mbstring
sudo systemctl restart apache2
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www/ktc.exchange/public
cd /var/www/www-root/data/www/ktc.exchange/public
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www/ktc.exchange/public
cd
sudo ln -s /usr/share/phpmyadmin /var/www/www-root/data/www/ktc.exchange/public
sudo systemctl restart nginx
pm2 list
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
nginx -t
sudo systemctl restart nginx
supervisorctl status
service nginx restart
bitcoin-cli getblockchaininfo
playcoin-cli stop
playcoin-cli getinfo
playcoin-clicomposer require denpa/laravel-playcoinrpc "^1.2"
cd /var/www/www-root/data/www/ktc.exchange/vendor/denpaw
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
cd ..
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
cd ..
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
php artisan vendor:publish --provider="Denpaw\Playcoin\src\Providers\ServiceProvider"
php artisan vendor:publish --provider="/var/www/www-root/data/www/ktc.exchange/vendor/Denpaw/Playcoin/src/Providers"
php artisan vendor:publish --provider="/var/www/www-root/data/www/ktc.exchange/vendor/Denpaw/Playcoin/Providers/ServiceProvider"
php artisan vendor:publish --provider="Denpaw\Playcoin\sreProviders\ServiceProvider"
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
php artisan vendor:publish --provider="Denpa\Playcoin\Providers\ServiceProvider"
php artisan vendor:publish --provider="Denpaw\Playcoin\src\Providers\ServiceProvider"
php artisan vendor:publish --provider="Denpa\Playcoin\Providers\ServiceProvider"
composer dump-autoload
php artisan vendor:publish --provider="Denpa\Bitcoin\Providers\ServiceProvider"
composer require denpa/laravel-bitcoinrpc "^1.2"
php artisan vendor:publish --provider="Denpa\Bitcoin\Providers\ServiceProvider"
npm run production
php artisan passport:install --force
composer dumpautoload -o
composer clearcache
composer dumpautoload -o
php artisan passport:install --force
npm run production
composer install
php artisan vendor:publish --provider="Denpa\Bitcoin\Providers\ServiceProvider"
npm run production
php artisan passport:install --force
pm2 log
php artisan config:cache
composer dumpautoload -o
\php artisan package:discover --ansi
composer clearcache
php artisan config:cache
npm install
php artisan migrate
php artisan passport:install --force
In ServiceProvider.php line 72:
php artisan migrate
In ServiceProvider.php line 72:
php artisan config:cache
php artisan db:seed
php artisan vendor:publish --provider="Bdenpa\laravel-bitcoinrpc\src\Providers"
delete bootstrap folder
composer install
php artisan config:cache
composer update
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
cd /var/www/www-root/data/www/ktc.exchange/vendor/Denpaw
cd vendor
cd Denpaw
cd denpaw
composer install
cd ..
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
cd /var/www/www-root/data/www/ktc.exchange
composer require denpaw/laravel-playcoinrpc "^1.2"
rm -r vendor
composer install
sudo   composer install
composer dump
composer update my/package
composer global update
composer install
composer global update
composer install
composer global update
rm -r vendor
composer global update
composer install
composer update --no-scripts
composer install --ignore-platform-reqs
composer install --no-scripts
composer require spatie/laravel-analytics
composer install
sudo composer install
sudo --user=www-data composer install
udo chown -R myuser: vendor/
sudo chown -R myuser: vendor/
sudo chown -R root: vendor/
sudo composer update
composer install --no-plugins --no-scripts ...
composer update --no-plugins --no-scripts ...
composer update
composer global update
composer global install
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
composer config repositories.private-packagist composer https://repo.packagist.com/denpaww/
composer config repositories.packagist.org false
composer config --global --auth http-basic.repo.packagist.com admecoin Show Token
composer require denpaw/laravel-playcoinrpc "^1.2"
composer config --global --auth http-basic.repo.packagist.com token e983f17be92d742957baddf3d9d6b5150de5cac5f625c496beaddee57c24
COMPOSER_AUTH='{"http-basic":{"repo.packagist.com":{"username":"token","password":"e983f17be92d742957baddf3d9d6b5150de5cac5f625c496beaddee57c24"}}}'
composer require denpaw/laravel-playcoinrpc "^1.2"
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
composer require denpaw/laravel-playcoinrpc "^1.2"
composer require denpaw/laravel-playcoinrpc
composer require denpaw/laravel-playcoinrpc "^1.2"
sudo composer require denpaw/laravel-playcoinrpc "^1.2"
composer require denpaw/laravel-playcoinrpc
composer require denpa/laravel-bitcoinrpc "^1.2"
composer require denpaw/laravel-playcoinrpc "^1.2"
composer require denpaw/laravel-playcoinrpc
cd vendor
cd dd
composer update
composer config --global --auth http-basic.repo.packagist.com admecoin Show Token
cd ..
cd ...
cd ..
composer config --global --auth http-basic.repo.packagist.com admecoin Show Token
composer config --global --auth http-basic.repo.packagist.com admecoin 5a4e2b7162f1f58fe30c257e1a9e9ca9a61d7704b50d1218ba7710299248
composer require denpaw/laravel-playcoinrpc "^1.2"
sudo composer require denpaw/laravel-playcoinrpc "^1.2"
composer require denpaw/laravel-playcoinrpc
composer require denpaw/laravel-playcoinrpc "^1.2"
cd vendor
git clone https://github.com/admecoin/denpa
cd denpaw
git clone https://github.com/admecoin/denpa
cd ..
cd denpaw
composer require denpaw/laravel-playcoinrpc "^1.2"
composer updae
composer update
cd ..
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
cd /var/www/www-root/data/www/ktc.exchange
composer require denpa/laravel-bitcoinrpc "^1.2"
php artisan vendor:publish --provider="Denpa\Bitcoin\Providers\ServiceProvider"
composer require denpaw/laravel-playcoinrpc "^1.2"
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
composer install
sudo rm -rf vendor/
sudo rm composer.lock
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
php artisan package:discover --ansi
sudo rm -rf vendor/
sudo rm composer.lock
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
composer require denpaw/laravel-playcoinrpc "^1.2"
cd /var/www/www-root/data/www/ktc.exchange/public/js
cd ..
composer require denpaw/laravel-playcoinrpc "^1.2"
composer update
composer require denpaw/laravel-playcoinrpc 
php artisan vendor:publish --provider="Denpaw\Playcoin\Providers\ServiceProvider"
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
composer update
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
playcoin-cli getbalance
playcoin-cli getinfo
playcoin-cli stop
playcoind -daemon
playcoin-cli getinfo
playcoin-cli getbalance
bitcoin-cli getbalance
pm2 log
playcoin-cli getinfo
playcoin-cli getbalance
rm -r tests
playcoin-cli stop
cd /var/www/www-root/data/www/ktc.exchange
php artisan migrate
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
php artisan key:generate
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan key:generate
php artisan migrate
php artisan key:generate
php artisan migrate
php artisan passport:install --force
php artisan migrate
php artisan key:generate
php artisan migrate
sudo rm -rf vendor/
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
rm -r app
sudo rm -rf vendor/
sudo rm composer.lock
composer install
sudo rm -rf vendor/
sudo rm composer.lock
composer install
bitcoin-cli getbalance
php artisan migrate
playcoin-cli getinfo
playcoin-cli stop
playcoind -daemon -reindfex
playcoin-cli getinfo
playcoin-cli stop
playcoind -daemon
playcoin-cli stop
playcoind -daemon
sudo rm -rf vendor/
sudo rm composer.lock
composer install
cd /var/www/www-root/data/www/ktc.exchange
sudo rm -rf vendor/
composer install
playcoin-cli getinfo
playcoin-cli stop
playcoind -daemon -reindex
playcoin-cli getinfo
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan exbita:create-admin
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan exbita:create-admin
playcoin-cli getinfo
sudo rm -rf vendor/
composer install
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan exbita:create-admin
playcoin-cli stop
playcoin-cli 
playcoin-cli getinfo
sudo rm -rf vendor/
composer install
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan exbita:create-admin
cd /var/www/www-root/data/www/ktc.exchange/storage
rm -r app
rm -r debugbar
rm -r  framework
cd /var/www/www-root/data/www/ktc.exchange/public
rm -r js
cd ..
sudo rm -rf vendor/
composer install
php artisan migrate
php artisan passport:install --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan exbita:create-admin
pm2 list
sudo chmod -R ug+rwx bootstrap/cache
php artisan db:seed
php artisan storage:link
php artisan config:cache
