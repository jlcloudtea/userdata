#!/bin/bash
# Test branch deployment hook, run after original AWS.zip extraction.
set -euo pipefail
install -d -m 750 -o apache -g apache /var/lib/aws-lab
curl -fsSL -o /var/www/html/index.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/student-registration/index.php
curl -fsSL -o /var/www/html/get-index-meta-data.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/student-registration/get-index-meta-data.php
chmod 644 /var/www/html/index.php /var/www/html/get-index-meta-data.php
