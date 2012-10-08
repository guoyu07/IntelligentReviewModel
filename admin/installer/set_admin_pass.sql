use mysql;
UPDATE user SET Password=PASSWORD("csip") WHERE User="root" AND Host="localhost";
UPDATE user SET Password=PASSWORD("csip") WHERE User="root" AND Host="itsdev5.vip.gatech.edu";
flush privileges;
create database its;
exit
