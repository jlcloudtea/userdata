#!/bin/bash -ex
# Updated to use Amazon Linux 2023
dnf update -y
dnf install -y httpd wget unzip git python3 python3-pip php-fpm php-mysqli php-json php php-devel
dnf install -y mariadb105-server
/usr/bin/systemctl enable httpd
/usr/bin/systemctl start httpd
cd /var/www/html
wget https://github.com/jlcloudtea/userdata/raw/refs/heads/main/AWS.zip
unzip AWS.zip -d /var/www/html/
chown apache:root /var/www/html/rds.conf.php

# Additive test feature: no changes to the extracted legacy application.
# Pin to reviewed commit / main before production deployment.
dnf install -y python3-boto3 || python3 -m pip install boto3 || true
git clone --depth 1 --branch feature/troubleshooting-portal-v1 https://github.com/jlcloudtea/userdata.git /tmp/userdata-portal
bash /tmp/userdata-portal/troubleshooting/install.sh
