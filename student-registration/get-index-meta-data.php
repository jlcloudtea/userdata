<?php
/* IMDSv2 instance metadata; no AWS credentials required. */
function readLabMetadata($path) {
    $endpoint='http://169.254.169.254/latest/';
    $tokenContext=stream_context_create(['http'=>[
        'method'=>'PUT', 'timeout'=>2,
        'header'=>"X-aws-ec2-metadata-token-ttl-seconds: 60\r\n"
    ]]);
    $token=@file_get_contents($endpoint.'api/token', false, $tokenContext);
    if ($token === false || trim($token) === '') return 'Unavailable';
    $context=stream_context_create(['http'=>[
        'timeout'=>2,
        'header'=>"X-aws-ec2-metadata-token: ".trim($token)."\r\n"
    ]]);
    $value=@file_get_contents($endpoint.'meta-data/'.$path, false, $context);
    return ($value === false || trim($value) === '') ? 'Unavailable' : trim($value);
}
echo '<table class="table table-bordered"><tr><th>Meta-Data</th><th>Value</th></tr>';
foreach (['Instance ID'=>'instance-id','Availability Zone'=>'placement/availability-zone'] as $label=>$path) {
    echo '<tr><td>'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'</td><td><i>'.
        htmlspecialchars(readLabMetadata($path), ENT_QUOTES, 'UTF-8').'</i></td></tr>';
}
echo '</table>';
