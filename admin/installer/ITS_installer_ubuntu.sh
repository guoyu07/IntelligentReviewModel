#!/bin/sh
#
# ITS installer
# Author(s): Gregory Krudysz
# Last Revision: May-22-2012
#=======================================================#

sudo apt-get -y install apache2 php5 libapache2-mod-php5
sudo /etc/init.d/apache2 restart
sudo apt-get -y install mysql-server php-pear

sudo apt-get -y install phpmyadmin
sudo cp /etc/phpmyadmin/apache.conf /etc/apache2/conf.d
sudo /etc/init.d/apache2 restart
sudo chmod 0777 /var/www

#-------------------------------------------------------#
# MySQL
#-------------------------------------------------------#
echo "Start MySQL ..."
/etc/init.d/mysql restart
#
#echo "Set MySQL passwords ..."
#/usr/bin/mysqladmin -u root password 'csip'
#/usr/bin/mysqladmin -u root -h itsdev1.vip.gatech.edu password 'csip'
#
echo "Set MySQL passwords ..."
/etc/init.d/mysql stop
mysqld_safe --skip-grant-tables --skip-networking &
sleep 3
mysql -u root < set_admin_pass.sql
#
/etc/init.d/mysql restart
#
echo "Load MySQL database ..."
mysql -u root -D its -pcsip < ITS_05-07-2012.sql
echo "... done ..."
#-------------------------------------------------------#
# MIMETEX
#-------------------------------------------------------#
sudo apt-get -y install texlive-full gedit-latex-plugin texmaker

# COPY mathtex.zip to /usr/lib/cgi-bin

sudo mkdir /var/www/cgi-bin
chmod 777 /var/www/cgi-bin
cp /var/www/html/admin/installer/cgi-bin/mimetex.cgi /var/www/cgi-bin/
cd /var/www/cgi-bin/
chmod 755 mimetex.cgi

sudo apt-get install linux-kernel-headers
sudo apt-get install build-essential
# MATHTEX
# RedHat:
yum -y install tetex tetex-IEEEtran tetex-afm tetex-dvipost tetex-dvips tetex-fonts tetex-latex tetex-perltex tetex-preview tetex-tex4ht tetex-unicode tetex-xdvi
