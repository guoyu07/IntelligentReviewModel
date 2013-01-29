#!/bin/sh
#
# ITS installer
# Author(s): Gregory Krudysz
# Last Revision: Jan-27-2013
#=======================================================#

sudo apt-get -y install apache2 php5 libapache2-mod-php5
sudo /etc/init.d/apache2 restart
sudo apt-get -y install mysql-server php-pear phpmyadmin
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
# cc mathtex.c
# mv a.out mathtex.cgi

sudo mkdir /var/www/cgi-bin
chmod 777 /var/www/cgi-bin
cp /var/www/html/admin/installer/cgi-bin/mimetex.cgi /var/www/cgi-bin/
cd /var/www/cgi-bin/
chmod 755 mimetex.cgi

sudo apt-get install linux-kernel-headers build-essential
# MATHTEX
# RedHat:
yum -y install tetex tetex-IEEEtran tetex-afm tetex-dvipost tetex-dvips tetex-fonts tetex-latex tetex-perltex tetex-preview tetex-tex4ht tetex-unicode tetex-xdvi

# X11 connection rejected because of wrong authentication:
# /etc/ssh/ssh_config
# ForwardX11 yes
# ForwardX11Trusted no

ls -l ~/.Xauthority
chown gte269x:gte269x ~/.Xauthority
chmod 0600 ~/.Xauthority

  18  sudo -u
   19  sudo ls
   20  su ls
   21  cd /etc/sudoers
   22  cd etc
   23  cd /etc
   24  ls
   25  gedit sudoers
   26  vi sudoers
   27  ll su*
   28  sudo -i
   29  sudo -i
   30  exit
   31  ls
   32  sudo -i
   33  ls -l ~/.Xauthority
   34  chown gte269x:gte269x ~/.Xauthority
   35  chmod 0600 ~/.Xauthority
   36  ls -l ~/.Xauthority
   37  cd /etc/network/
   38  ls
   39  geidt interfaces 
   40  gedit interfaces 
   41  ls
   42  gedit interfaces &
   43  sudo -i
   44  exit
   45  clock
   46  xeyes
   47  sudo apt-get install x11-apps
   48  xeyes
   49  mysql -u root -p
   62  sudo -i
   63  cd /etc/
   64  gedit vsftpd.conf 
   65  sudo gedit vsftpd.conf 
   66  sudo /etc/init.d/vsftpd restar
   67  sudo /etc/init.d/vsftpd restart
   68  pwd
   72  gedit /etc/ssh/ssh_config &
   73  sudo gedit /etc/ssh/ssh_config &
