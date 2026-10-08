# userdata

This is the user data for CLD401ACF subject.

`AWS.zip` includes the Assessment 2 first-visit student registration, IMDSv2
instance metadata, lab tab descriptions and updated S3 heading. Load Test,
RDS, S3 logic and other existing assets are preserved.

Deploy `userdata.sh` on Amazon Linux 2023. It installs the dependencies,
extracts the complete website and creates `/var/lib/aws-lab` for locally
stored student details. No student-registration branch overlays are needed.
Registration is self-declared, not verified AWS authentication.

If extracting the ZIP manually on an existing assessment EC2, also run:

```bash
sudo install -d -m 750 -o apache -g apache /var/lib/aws-lab
```

The separate Troubleshooting validation feature is not included yet.
