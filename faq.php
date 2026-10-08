<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FAQ &amp; Tips — CLD401ACF Assessment 2</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <style>
    .faq-content{max-width:850px;margin:25px auto 45px;line-height:1.6}
    .faq-content h1{font-size:28px}.faq-content h2{font-size:20px}
    .faq-content details{border-bottom:1px solid #ddd;margin:12px 0;padding-bottom:12px}
    .faq-content summary{padding:14px;border:1px dashed #0078d7;border-radius:5px;background:#f9f9f9;cursor:pointer;font-weight:600;color:#244765}
    .faq-content summary:focus-visible{outline:3px solid #0078d7;outline-offset:3px}
    .faq-answer{padding:12px 16px 0}.faq-answer li{margin-bottom:8px}
    .faq-content code{overflow-wrap:anywhere}
    @media(max-width:767px){.navbar-collapse.collapse{display:block!important}.faq-answer{padding:12px 4px 0}.faq-content h1{font-size:24px}}
  </style>
</head>
<body>
<div class="container">
  <?php include 'menu.php'; ?>
  <main class="faq-content">
    <h1>Assessment FAQ &amp; Tips</h1>
    <p>Use these reminders alongside your assessment document and the learning materials on Learn.</p>
    <div class="alert alert-info">
      <h2>Before you start</h2>
      <ul>
        <li>All AWS Academy Labs 1–6 are important preparation for completing this assessment.</li>
        <li>Additional activities may be required in separate topics on Learn, including the <strong>storage extension activity</strong> and <strong>S3 bucket activity</strong> in <strong>Topic 11 — Storage Resources</strong>.</li>
        <li>Check the assessment requirements and save screenshots and other required evidence as you complete each task.</li>
      </ul>
    </div>
    <details>
      <summary>What should I do if my Learner Lab credits run out?</summary>
      <div class="faq-answer"><ul>
        <li>If depleted credits prevent access to Learner Lab, use the alternative environment specified by your lecturer: <strong>Lab 5 — Build a Database Server</strong> when you need Multi-AZ database functionality, or the <strong>Sandbox</strong> in the AWS Academy Cloud Foundations course for the troubleshooting task. The Sandbox does not support the Multi-AZ database requirement for this assessment.</li>
        <li>These alternative labs do not preserve your configurations after they stop. Save required screenshots and other evidence before the session ends; do not rely on AWS snapshots surviving the reset.</li>
        <li>For Assessment 2, Task 6, you can use these alternatives and provide evidence of accessing the file from a different browser or a private/incognito window.</li>
        <li>If the alternative lab restricts a required service or setting, contact your lecturer before proceeding.</li>
      </ul></div>
    </details>
    <details>
      <summary>Why can't I access my EC2 web server using its public IP address or DNS name?</summary>
      <div class="faq-answer"><ol>
        <li>Ensure the EC2 instance is in a public subnet in a VPC with an attached Internet Gateway.</li>
        <li>Check that the subnet's associated route table has a route for <code>0.0.0.0/0</code> to the Internet Gateway.</li>
        <li>Leave the default network ACL unchanged unless instructed otherwise. If it has been changed, check that it does not block request or return traffic.</li>
        <li>Ensure the instance has a public IP address and use its current address.</li>
        <li>For this assessment lab, verify that the instance security group allows inbound HTTP on TCP port 80 from <code>0.0.0.0/0</code>, as required by the assessment document, and retains the default outbound access.</li>
        <li>Check that User Data matches the assessment instructions. Put the <code>#!/bin/bash</code> line on its own line; put commands on following lines.</li>
        <li>Allow approximately 2–4 minutes for installation; package downloads and updates may take longer. Check <code>/var/log/cloud-init-output.log</code> if the website does not become available.</li>
        <li>Use <code>http://&lt;public-ip&gt;/</code> or <code>http://&lt;public-dns&gt;/</code>. HTTPS is not configured by this website's installation script.</li>
        <li>Try a different browser or private/incognito mode to avoid cached results or automatic HTTPS upgrades.</li>
        <li>If these checks do not resolve the problem, save your evidence and review the installation logs. If instructed, test a new EC2 instance with the correct settings. Avoid leaving unused test instances running.</li>
      </ol></div>
    </details>
    <details>
      <summary>Why does creating my RDS database fail in Learner Lab?</summary>
      <div class="faq-answer"><p>Check the settings required for this assessment:</p><ul>
        <li>Select the <strong>MySQL</strong> database engine.</li>
        <li>Select the <strong>Dev/Test</strong> creation template, rather than Production.</li>
        <li>Choose a <strong>Multi-AZ DB instance</strong>, rather than a Multi-AZ DB cluster.</li>
        <li>Select <strong>Burstable classes</strong> and <strong>db.t3.micro</strong>, where available and permitted by the lab.</li>
        <li>Set allocated storage to <strong>20 GiB</strong>, rather than 200 GiB, as specified in the assessment.</li>
        <li>Provide the required <strong>initial database name</strong>.</li>
        <li>Compare the steps with your Lab 5 instructions. If the requested option is unavailable or an error persists, record the error and contact your lecturer.</li>
      </ul></div>
    </details>
    <details>
      <summary>Why does the RDS connection form return to the same page?</summary>
      <div class="faq-answer"><ul>
        <li>Confirm that the database engine is <strong>MySQL</strong> and the database is available.</li>
        <li>Ensure the DB subnet group contains the required private subnets in the same VPC as the web server.</li>
        <li>Check that the RDS security group allows <strong>MySQL TCP port 3306</strong> from the web server's security group.</li>
        <li>In the website's RDS configuration form, use the actual <strong>database name</strong>, not the DB instance identifier.</li>
        <li>Check the RDS endpoint, username and password against your database settings.</li>
      </ul></div>
    </details>
    <details>
      <summary>How do I reset the troubleshooting environment if the script fails to create resources?</summary>
      <div class="faq-answer"><p><strong>Resetting deletes the troubleshooting stack and your changes to it. Save your assessment evidence first.</strong></p><ol>
        <li>Ensure the lab session is running and use the script provided in the assessment instructions.</li>
        <li>Run the script and select <strong>option 2 — Delete Troubleshooting Stack</strong>.</li>
        <li>Wait for deletion to finish; it may take several minutes. Check CloudFormation to confirm the <code>troubleshoot</code> stack has been deleted, and confirm its VPC is no longer present. If deletion fails, review the stack events and contact your lecturer.</li>
        <li>Run the script again and select <strong>option 1 — Create Troubleshooting Stack</strong>.</li>
        <li>Wait for the script to confirm the environment is ready. If creation fails, save the error message and contact your lecturer.</li>
      </ol></div>
    </details>
  </main>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
