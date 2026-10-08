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

# CLD401ACF registration feature — test branch only.
dnf install -y curl
curl -fsSL https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/student-registration/install.sh | bash
