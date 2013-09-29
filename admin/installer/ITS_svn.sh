#!/bin/sh
#
# ITS svn
# Author(s): Gregory Krudysz
# Last Revision: June-30-2011
#------------------------------------#
yum -y install mod_dav_svn  # WebDAV
#---------------------------#
# REPO at "/home/user/svn"
#---------------------------#
echo "SVN setup"
mkdir /home/user
svnadmin create --fs-type fsfs /home/user/svn
svn ls file:///home/user/svn

echo "SVN initial import"
svn import /var/www/html/ITS file:///home/user/svn/ITS/trunk -m 'Initial import'

svn checkout file:///home/user/svn/ITS  # Take out files from REPO and load them into working dir: /home/user/svn/ITS
svn commit -m 'ITS version 1.61'        # From inside the working dir: /home/user/svn/ITS

chown -R apache:apache /home/user/svn/*
chmod -R 775 /home/user/svn/*
chcon -R -h -t httpd_sys_content_t /home/user/svn
chcon -R -h -t httpd_sys_content_t /home/user/svn

chcon -h system_u:object_r:httpd_sys_content_t /home/user/svn
chcon -R -h root:object_r:httpd_sys_content_t /home/user/svn*

sudo chown -R apache:apache /home/user/svn/ITS
sudo chmod -R g+ws /home/user/svn/ITS         # recursive group write

#---------------------------#
# SVN server Apache setup, add to /etc/ .. httpd.conf
#---------------------------#
LoadModule dav_module modules/mod_dav.so
LoadModule dav_svn_module modules/mod_dav_svn.so

<Location /svn>
    DAV svn

    # All repos subdirs of /home/user/svn
    SVNParentPath "/home/user/svn"   # !! change to SVNPath for a ** SINGLE ** REPO
		SVNListParentPath on
</Location>
#---------------------------#
