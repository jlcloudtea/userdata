#!/bin/bash
# Additive installation without replacing original AWS.zip files.
set -euo pipefail
SOURCE_DIR="$(cd "$(dirname "$0")" && pwd)"
install -d -m 755 /var/www/html/troubleshooting /opt/aws-lab-portal
install -m 644 "$SOURCE_DIR/index.php" /var/www/html/troubleshooting/index.php
install -m 644 "$SOURCE_DIR/api.php" /var/www/html/troubleshooting/api.php
install -m 644 "$SOURCE_DIR/validator.py" /opt/aws-lab-portal/validator.py
echo "Page installed at /troubleshooting/; AWS validation requires boto3 and a read-only server IAM role."
