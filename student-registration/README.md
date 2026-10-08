# CLD401ACF Assessment 2 — First-visit student identification

## Behaviour
On first visit to the root homepage, students see:
- **Welcome to CLD401ACF Assessment 2**
- **Please identify yourself before starting your lab.**
- Required **Student Name or Email** field
- Placeholder: `e.g. Jane.Smith@student.tafesa.edu.au`

Submitting saves the provided value to `/var/lib/aws-lab/student.json`, outside the web root. Subsequent visits show the name/email on the original homepage alongside EC2 Instance ID, AZ, and CPU load. An Edit Student Details link allows correction.

**Important**: This identifies the student *by self-declaration*, not a verified AWS Federated User. Anyone who can access the public webpage can submit or edit it. It is suitable as a convenience label for a lab screenshot but **not** as proof of authorship or identity.

## EC2 test
Launch a fresh Amazon Linux 2023 instance using **this test branch's user data**:
`https://raw.githubusercontent.com/jlcloudtea/userdata/feature/cld401acf-student-registration/userdata.sh`

It installs the original `AWS.zip` from main, and then downloads two PHP overrides and creates the writable private directory. The main branch and original ZIP remain unchanged.

Make sure the EC2 Security Group permits HTTP 80 from the relevant client network. On a live instance, test PHP with:
`sudo php -l /var/www/html/index.php`
`sudo php -l /var/www/html/get-index-meta-data.php`

Test initial form, saved student display on refresh, Edit Student Details, Load Test, RDS, and S3 pages. Verify IMDSv2 returns Instance ID and Availability Zone.

Known limitation: data lives only on that one EC2; it does not follow Auto Scaling instance replacement. Do not display real student names on a broadly public site unless access protections are appropriate.
