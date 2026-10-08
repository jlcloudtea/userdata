# Troubleshooting Portal — test branch

An **additive** test feature for the existing Amazon Linux 2023 Apache/PHP website. The original `AWS.zip` is untouched and its Load Test / RDS functionality is retained.

## Test branch

`feature/troubleshooting-portal-v1`

The branch's `userdata.sh` installs the original application first and then installs the new portal at:

`http://<portal-server-public-ip>/troubleshooting/`

**Important:** Since the original PHP/HTML resides in the binary `AWS.zip` and was not available for source-level review, this test adds its own navbar **inside the new Troubleshooting page only**. It does *not* alter the original homepage/navbar. Upload or extract the original ZIP if you want the existing homepage updated with a native third tab.

## Information shown

- **Portal user**: `REMOTE_USER` from existing HTTP authentication, if configured; otherwise `Not signed in`. EC2 Linux account `ec2-user` is not a browser login.
- **Portal instance ID / AZ**: securely fetched via IMDSv2 on the portal EC2; **not** the target student EC2.
- **Five read-only checks**: Security Group HTTP 80; public route table; live ASG capacity; 09:00/11:00 UTC daily scheduled actions. The checks use the physical resource IDs from CloudFormation stack `troubleshoot`.

## Authentication / cross-account prerequisite

The validator uses Boto3's **server-side** AWS credential chain. It needs permission to access the **same AWS account as the student's stack**, or a properly approved cross-account read-only role. Different Learner Lab accounts **cannot** be verified from the portal's own instance profile without explicit authorization. Never collect students' access keys in a public form.

Required read permissions:
- `cloudformation:DescribeStackResource`
- `ec2:DescribeSecurityGroups`, `ec2:DescribeRouteTables`
- `autoscaling:DescribeAutoScalingGroups`, `autoscaling:DescribeScheduledActions`

Without this access the page displays **Unavailable**, not Fail.

No remote public HTTP test is performed. HTTP check verifies the expected security-group rule; Internet routing is checked separately. A Pass does not prove Apache is reachable remotely.

## Test steps

1. Deploy a new portal EC2 with the **branch** `userdata.sh`, or run `bash troubleshooting/install.sh` as root on an existing copy of the site.
2. Open `/troubleshooting/`. Ensure legacy site still works at `/`.
3. Check Portal Instance ID/AZ. Verify that username reads `Not signed in` unless HTTP login exists.
4. If IAM permissions and the stack are in the portal's AWS account, expect live Pass/Fail.
5. If they are not, expect **Unavailable**. Do not treat Unavailable as student failure.
6. Validate the original AWS site and database features have not regressed before merging.

For a central portal spanning different student accounts, the next design step is a vetted authenticated validation transport (e.g., centrally provisioned role or signed local validation reports). This branch intentionally does not implement unsafe student-provided access credentials.

The prior experimental draft PR on `jlcloudtea/AWSFSTroubleshooting` is separate; it does not need to be merged for this portal prototype.
