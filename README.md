# CLD401ACF Assessment 2 — AWS Lab Website

A PHP assessment website deployed to an Amazon Linux 2023 EC2 instance using `userdata.sh`. The complete website is packaged in `AWS.zip`; deployment does not require downloading feature-branch overlays.

## Features

- First visit displays **Welcome to CLD401ACF Assessment 2** and asks for **Student Name or Email**.
- Student details are stored locally in `/var/lib/aws-lab/student.json`, outside the webroot, and shown on subsequent homepage visits. There is no student edit button.
- The homepage shows Instance ID and Availability Zone using IMDSv2, CPU load and assessment tab descriptions.

| Tab | Purpose |
| --- | --- |
| Home | Student information, instance metadata and lab guide |
| Load Test | Task 4 — Auto Scaling |
| RDS | Task 3 — Database connection |
| S3 | Tasks 5 & 6 — Online storage; Verify S3 Object Public Access |

The existing Load Test, RDS and S3 functionality is preserved. The planned Troubleshooting validation tab is **not included in this release**.

## Deploy a new EC2

1. Launch an Amazon Linux 2023 EC2 instance in your AWS Academy Learner Lab.
2. Configure networking so you can reach the instance and allow inbound HTTP TCP 80 from your testing network.
3. For the planned AWS validation feature, select the existing `LabInstanceProfile` under **Advanced details → IAM instance profile**. It is not required for the current registration and IMDSv2 display features.
4. Copy the contents of [`userdata.sh`](userdata.sh) into the EC2 **User data** field before launching.
5. Wait for installation to finish, then open `http://<instance-public-ip>/` and register your name or email.

User Data installs Apache, PHP, database packages and unzip; downloads `AWS.zip` from `main`; extracts the website; and creates its private registration directory. The instance needs outbound connectivity to the package repositories and GitHub. Installation logs are available at `/var/log/cloud-init-output.log`.

## Update an existing assessment EC2

Back up existing website files and any RDS configuration before replacing them. The ZIP contains `rds.conf.php`, so extracting it over an existing installation can replace that configuration. Student details are stored separately and are not inside the ZIP.

After extracting the package into `/var/www/html`, ensure the private directory exists:

```bash
sudo install -d -m 750 -o apache -g apache /var/lib/aws-lab
sudo chown apache:root /var/www/html/rds.conf.php
sudo php -l /var/www/html/index.php
sudo php -l /var/www/html/get-index-meta-data.php
```

Changing GitHub files does not automatically update an existing EC2. User Data normally runs during the first boot.

## Student identity and limitations

Registration is self-declared; it does not authenticate the student against their AWS Academy login. The label belongs to this EC2 instance, rather than an individual browser, and does not automatically follow an Auto Scaling replacement. Restrict access appropriately when displaying student information.

## Verification

The repackaged ZIP has been checked for archive integrity and preserved entry names. Only the registration homepage, IMDSv2 metadata file and S3 heading were updated; other archive contents were checked byte-for-byte. Shell syntax checks passed. A fresh EC2 deployment of this repackaged release still needs a smoke test covering registration, metadata and all existing tabs.
