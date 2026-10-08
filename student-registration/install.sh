#!/bin/bash
# Test branch deployment hook, run after original AWS.zip extraction.
set -euo pipefail
install -d -m 750 -o apache -g apache /var/lib/aws-lab
curl -fsSL -o /var/www/html/index.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/student-registration/index.php
curl -fsSL -o /var/www/html/get-index-meta-data.php https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/student-registration/get-index-meta-data.php
# Preserve the original S3 functionality; update the page heading only.
if [ -f /var/www/html/s3.php ]; then
  sed -i 's|<h3>Test S3 Link</h3>|<h3>Verify S3 Object Public Access</h3>|' /var/www/html/s3.php
fi
chmod 644 /var/www/html/index.php /var/www/html/get-index-meta-data.php
