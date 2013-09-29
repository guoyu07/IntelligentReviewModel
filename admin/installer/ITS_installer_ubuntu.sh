#!/bin/sh
#
# ITS installer
# Author(s): Gregory Krudysz
# Last Revision: Feb-22-2013
#=======================================================#

sudo apt-get -y install apache2 php5 libapache2-mod-php5 php5-curl python-mysqldb geany meld vim
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
service mysql stop
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
# PYTHON
#-------------------------------------------------------#
sudo apt-get install python python-numpy python-scipy

#-------------------------------------------------------#
# Kerberos Client
#-------------------------------------------------------#
sudo apt-get install krb5-user libpam-krb5 libpam-ccreds auth-client-config
cp /var/www/html/admin/installer/krb5.conf /etc/krb5.conf
sudo /etc/init.d/krb5-admin-server restart


#/usr/bin/system-config-authentication
# check the "Enable Kerberos" under the Authentication Tab: run /usr/bin/system-config-authentication
#-------------------------------------------------------#
# MATHTEX
#-------------------------------------------------------#
sudo apt-get -y install texlive-full gedit-latex-plugin texmaker screen-kernel-headers build-essential

# COPY mathtex.zip to /usr/lib/cgi-bin
# cc mathtex.c
# mv a.out mathtex.cgi																																																																					

sudo mkdir /var/www/cgi-bin
chmod 777 /var/www/cgi-bin
cp /var/www/html/admin/installer/cgi-bin/mathtex.c /usr/lib/cgi-bin
sudo cc /usr/lib/cgi-bin/mathtex.c
sudo /usr/lib/cgi-bin/a.out /usr/lib/cgi-bin/mathtex.cgi 

# MATHTEX
# RedHat:
yum -y install tetex tetex-IEEEtran tetex-afm tetex-dvipost tetex-dvips tetex-fonts tetex-latex tetex-perltex tetex-preview tetex-tex4ht tetex-unicode tetex-xdvi

#-------------------------------------------------------#
# Server configuration: 
#-------------------------------------------------------#
Alias /icons/ "/var/www/icons/"
<Directory "/var/www/icons">
    Options Indexes MultiViews
    AllowOverride None
    Order allow,deny
    Allow from all
</Directory>

Alias /ITS_FILES/ "/var/www/ITS-RESOURCES/ITS_FILES/"
<Directory "/var/www/ITS-RESOURCES/ITS_FILES">
    Options Indexes MultiViews
    AllowOverride None
Order allow,deny
Allow from all
</Directory>

Alias /VIP/ "/var/www/VIP/"
<Directory "/var/www/VIP">
    Options Indexes MultiViews
    AllowOverride None
Order allow,deny
Allow from all
</Directory>

Alias /VM/ "/var/www/VM/"
<Directory "/var/www/VM">
    Options Indexes MultiViews
    AllowOverride None
Order allow,deny
Allow from all
</Directory>

Alias /git/ "/opt/git/"
<Directory "/opt/git">
    Options Indexes MultiViews
    AllowOverride None
Order allow,deny
Allow from all
</Directory>

Alias /gitweb/ "/var/www/gitweb/"
    <Directory /var/www/gitweb>
        Options ExecCGI +FollowSymLinks +SymLinksIfOwnerMatch
        AllowOverride All
        order allow,deny
        Allow from all
        AddHandler cgi-script cgi
        DirectoryIndex gitweb.cgi
    </Directory>
#-------------------------------------------------------#
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
   25  gedit sudoers
   26  vi sudoers
   27  ll su*
   32  sudo -i
   33  ls -l ~/.Xauthority
   34  chown gte269x:gte269x ~/.Xauthority
   35  chmod 0600 ~/.Xauthority
   36  ls -l ~/.Xauthority
   37  cd /etc/network/
   42  gedit interfaces &
   47  sudo apt-get install x11-apps
   49  mysql -u root -p
   63  cd /etc/
   64  gedit vsftpd.conf 
   65  sudo gedit vsftpd.conf 
   66  sudo /etc/init.d/vsftpd restar
   67  sudo /etc/init.d/vsftpd restart
   72  gedit /etc/ssh/ssh_config &
   73  sudo gedit /etc/ssh/ssh_config &
