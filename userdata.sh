#!/bin/bash -ex
# Updated to use Amazon Linux 2023
dnf update -y
dnf install -y httpd wget php-fpm php-mysqli php-json php php-devel
dnf install -y mariadb105-server
/usr/bin/systemctl enable httpd
/usr/bin/systemctl start httpd
cd /var/www/html
wget https://github.com/jlcloudtea/userdata/raw/refs/heads/main/AWS.zip
unzip AWS.zip -d /var/www/html/
chown apache:root /var/www/html/rds.conf.php

# Test-only homepage overrides (retain all original Load Test, RDS and S3 assets)
wget -q -O /var/www/html/index.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/homepage-user-account-v1/homepage-overrides/index.php
wget -q -O /var/www/html/get-index-meta-data.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/homepage-user-account-v1/homepage-overrides/get-index-meta-data.php
