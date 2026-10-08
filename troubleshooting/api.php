<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
// Uses only the server's IAM role / AWS credentials; no browser-supplied credentials.
$output = []; $status = 0;
exec('/usr/bin/python3 /opt/aws-lab-portal/validator.py 2>/dev/null', $output, $status);
if ($status !== 0 || !$output) {
  http_response_code(503);
  echo json_encode(['validation_available'=>false,'checks'=>[]]);
} else { echo end($output); }
