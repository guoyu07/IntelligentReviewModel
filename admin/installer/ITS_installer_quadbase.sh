#!/bin/sh
#
# ITS installer
# Author(s): Gregory Krudysz
# Last Revision: Jan-21-2012
#=======================================================#

sudo apt-get install apache2
sudo apt-get install php5 libapache2-mod-php5
sudo /etc/init.d/apache2 restart
sudo apt-get install mysql-server
sudo apt-get install php-pear

sudo apt-get install phpmyadmin
sudo cp /etc/phpmyadmin/apache.conf /etc/apache2/conf.d
sudo /etc/init.d/apache2 restart
sudo apt-get install curl
curl http://localhost/phpmyadmin/

chmod 0777 /var/www/html
chmod 755 *
#-------------------------------------------------------#
# Install required packages
#-------------------------------------------------------#
echo "Remove packages from ITS_package_list.txt ..."
yum -y remove php php-common mysql
#
echo "Install packages from ITS_package_list.txt ..."
yum -y install `cat ITS_package_list.txt`

yum -y install mysql
yum -y install mysql-server
yum -y install php-pear

#-------------------------------------------------------#
# Kerberos passwords
#-------------------------------------------------------#
cp krb5.conf /etc/krb5.conf
/usr/bin/system-config-authentication
# check the "Enable Kerberos" under the Authentication Tab: run /usr/bin/system-config-authentication
adduser `cat ITS_user_list.txt`
#-------------------------------------------------------#
# MySQL
#-------------------------------------------------------#
service httpd start
echo "Start MySQL ..."
/etc/init.d/mysqld restart
#
#echo "Set MySQL passwords ..."
#/usr/bin/mysqladmin -u root password 'csip'
#/usr/bin/mysqladmin -u root -h itsdev1.vip.gatech.edu password 'csip'
#
echo "Set MySQL passwords ..."
service mysqld stop
mysqld_safe --skip-grant-tables --skip-networking &
sleep 3
mysql -u root < set_admin_pass.sql
#
service mysqld restart
#
#echo "start the MySQL daemon ..."
#cd /usr ; /usr/bin/mysqld_safe
#
echo "Load MySQL database ..."
mysql -u root -D its -pcsip < ITS_VIP_02-12-2011.sql
echo "... done ..."

#-------------------------------------------------------#
# MDB2
#-------------------------------------------------------#
pear install MDB2_driver_mysql
pear install --alldeps MDB2_Driver_mysql
#-------------------------------------------------------#
# MIMETEX
#-------------------------------------------------------#
cp /var/www/html/admin/installer/cgi-bin/mimetex.cgi /var/www/cgi-bin/
cd /var/www/cgi-bin/
chmod 755 mimetex.cgi

#-------------------------------------------------------#
# Media Wiki
#-------------------------------------------------------#
MW_file=`ls mediawiki-*.tar.gz`
mkdir /var/www/html/wikimedia
chmod u+x /var/www/html/wikimedia
tar xzf $MW_file -C /var/www/html/wikimedia/
cd /var/www/html/wikimedia/mediawiki-1.16.5
chmod a+w config
echo "... done ..."

#-------------------------------------------------------#
# PDF2TXT
#-------------------------------------------------------#
yum -y install poppler-utils

#-------------------------------------------------------#
# InCommon - Shibboleth
#-------------------------------------------------------#
# IdP - Identity Provider
yum makecache && yum search openjdk

#-------------------------------------------------------#
# MATLAB
#-------------------------------------------------------#
# run ML with: ./matlab
yum -y install libXp.x86_64 unixODBC unixODBC-kde
# run: ODBCConfig and configure ODBC with a GUI

#-------------------------------------------------------#
# PYTHON
#-------------------------------------------------------#
yum -y install python-numeric python-numpy python-scipy
# Place *.repo file into /etc/yum.repo.d/, and then install numpy/scipy with yum:


#-------------------------------------------------------#
# Shibboleth
#-------------------------------------------------------#

# public-key access
mkdir ~root/.ssh
chmod 700 ~root/.ssh  
ssh-add -L >> ~root/.ssh/authorized_keys
exec ssh-agent bash				

# create shibboleth repo and install
echo "[shibboleth]
name=Shibboleth
baseurl=http://download.opensuse.org/repositories/security://shibboleth/RHEL_5
gpgcheck=0
enabled=1
" >> /etc/yum.repos.d /shib.repo						

yum -y install shibboleth.x86_64

# create "secure" dir
mkdir /var/www/html/secure/ 

#-------------------------------------------------------#
# SSL
#-------------------------------------------------------#
# Check if Apache is ssl enabled, and install

/usr/sbin/httpd -l

yum -y install mod_ssl  
# ssl config file: /etc/httpd/conf.d/ssl.conf

# remove temporary keys that were created at Linux install

mkdir /etc/httpd/conf/ssl.key
mkdir /etc/httpd/conf/ssl.crt

# remove default ssl config files ( if they exist )
rm /etc/httpd/conf/ssl.key/server.key
rm /etc/httpd/conf/ssl.crt/server.crt

# create key and set permissions, with PASSWORD "csip"
# ref: http://www.sitepoint.com/securing-apache-2-server-ssl/
# NOTE: Cannot have multiple secure virtual hosts on the same SOCKET (IP address + port).

openssl genrsa -des3 -out /etc/httpd/conf/ssl.key/itsdev1.vip.gatech.edu.key 1024 
chmod 400 /etc/httpd/conf/ssl.key/itsdev1.vip.gatech.edu.key
openssl req -new -key /etc/httpd/conf/ssl.key/itsdev1.vip.gatech.edu.key -x509 -out /etc/httpd/conf/ssl.crt/itsdev1.vip.gatech.edu.crt

# -- Enter Certificate specific details --

Country Name (2 letter code) [GB]:US
State or Province Name (full name) [Berkshire]:Georgia
Locality Name (eg, city) [Newbury]:Atlanta
Organization Name (eg, company) [My Company Ltd]:Georgia Institute of Technology
Organizational Unit Name (eg, section) []:EDU
Common Name (eg, your name or your server's hostname) []:itsdev1.vip.gatech.edu
Email Address []:its-gt@gatech.edu

# -- ---------------------------------- -- #

# Copy into /etc/httpd/conf/httpd.conf
<IfModule mod_ssl.c>
<VirtualHost *:443>
  ServerName itsdev1.vip.gatech.edu
  SSLEngine on
  SSLCertificateFile /etc/httpd/conf/ssl.crt/itsdev1.vip.gatech.edu.crt
  SSLCertificateKeyFile /etc/httpd/conf/ssl.key/itsdev1.vip.gatech.edu.key
  SetEnvIf User-Agent ".*MSIE.*" nokeepalive ssl-unclean-shutdown
  RewriteEngine on

  RewriteRule ^/(.*)logout http://itsdev1.vip.gatech.edu:80/$1logout [NC,R=301,L]
  RewriteRule ^/(.*) http://localhost:8080/VirtualHostBase/https/localhost:443/its/VirtualHostRoot/$1 [P,L]

</VirtualHost>
</IfModule>

service httpd stop
service httpd startssl
service httpd restart
