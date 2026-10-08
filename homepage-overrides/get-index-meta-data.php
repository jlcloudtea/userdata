<?php
/*
 * Lab homepage metadata (Amazon Linux 2 / 2023, IMDSv2 compatible).
 * STUDENT_ACCOUNT is an optional demonstration label, NOT AWS authenticated identity.
 * For production authentication, integrate an identity provider and a server-side session.
 */
function lab_metadata($path) {
    $base = 'http://169.254.169.254/latest/';
    $tokenCtx = stream_context_create(array('http' => array(
        'method' => 'PUT', 'timeout' => 2, 'ignore_errors' => true,
        'header' => "X-aws-ec2-metadata-token-ttl-seconds: 60\r\n"
    )));
    $token = @file_get_contents($base . 'api/token', false, $tokenCtx);
    if ($token === false || trim($token) === '') {
        return 'Unavailable';
    }
    $metadataCtx = stream_context_create(array('http' => array(
        'timeout' => 2, 'ignore_errors' => true,
        'header' => 'X-aws-ec2-metadata-token: ' . trim($token) . "\r\n"
    )));
    $value = @file_get_contents($base . 'meta-data/' . $path, false, $metadataCtx);
    return ($value !== false && trim($value) !== '') ? trim($value) : 'Unavailable';
}

/* Set STUDENT_ACCOUNT in Apache/PHP environment or edit the demonstration value below. */
$studentAccount = getenv('STUDENT_ACCOUNT');
if ($studentAccount === false || trim($studentAccount) === '') {
    $studentAccount = 'abc@stu.tafesa.edu.au'; // DEMO ONLY, not the visitor's verified identity.
}
$instanceId = lab_metadata('instance-id');
$availabilityZone = lab_metadata('placement/availability-zone');

function lab_meta_row($name, $value) {
    echo '<tr><td>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</td><td><i>' .
        htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</i></td></tr>';
}

echo '<table class="table table-bordered">';
echo '<tr><th>Meta-Data</th><th>Value</th></tr>';
lab_meta_row('User Account (Demo)', $studentAccount);
lab_meta_row('InstanceId', $instanceId);
lab_meta_row('Availability Zone', $availabilityZone);
echo '</table>';
echo '<p class="text-muted"><small>User Account is a configurable demonstration value, not an authenticated AWS or website login.</small></p>';
?>
