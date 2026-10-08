<?php
function imds($path) {
  $ctx=stream_context_create(['http'=>['method'=>'PUT','timeout'=>1,'header'=>"X-aws-ec2-metadata-token-ttl-seconds: 60\r\n"]]);
  $token=@file_get_contents('http://169.254.169.254/latest/api/token',false,$ctx);
  if (!$token) return 'Unavailable';
  $ctx=stream_context_create(['http'=>['timeout'=>1,'header'=>'X-aws-ec2-metadata-token: '.trim($token)."\r\n"]]);
  $value=@file_get_contents('http://169.254.169.254/latest/meta-data/'.$path,false,$ctx);
  return htmlspecialchars($value ?: 'Unavailable',ENT_QUOTES,'UTF-8');
}
$instance=imds('instance-id'); $az=imds('placement/availability-zone');
$user=htmlspecialchars($_SERVER['REMOTE_USER'] ?? 'Not signed in',ENT_QUOTES,'UTF-8');
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>AWS Lab Troubleshooting</title>
<style>body{font:16px Arial,sans-serif;background:#f4f5f7;color:#273342;margin:0}nav{background:white;padding:20px 30px;display:flex;gap:30px;align-items:center;border-bottom:1px solid #ddd}nav a{color:#536171;text-decoration:none;font-size:19px}nav a.active{font-weight:700;color:#172c4c}main{max-width:960px;margin:32px auto;padding:0 16px}section{background:white;padding:24px;margin:0 0 18px;border:1px solid #ddd;border-radius:8px}table{width:100%;border-collapse:collapse}td,th{padding:12px;border:1px solid #ddd;text-align:left}.pass{color:green;font-weight:bold}.fail{color:#bd2323;font-weight:bold}.unavailable{color:#666}button{background:#232f3e;color:white;border:0;border-radius:4px;padding:11px 16px;cursor:pointer}</style>
</head><body><nav><strong style="font-size:28px">aws</strong><a href="/">Load Test / RDS</a><a class="active" href="/troubleshooting/">Troubleshooting</a></nav>
<main><section><h1>Erfys Confectionary — Troubleshooting Lab</h1><p>Check EC2, VPC routing and Auto Scaling tasks. Results are limited to Pass / Fail when AWS validation is available.</p>
<table><tr><th>Portal user</th><td><?= $user ?></td></tr><tr><th>Portal Instance ID</th><td><?= $instance ?></td></tr><tr><th>Portal Availability Zone</th><td><?= $az ?></td></tr></table><p><small>Metadata identifies this portal server, not the student's troubleshooting instance. Username requires existing web authentication.</small></p></section>
<section><h2>Validation</h2><p id="summary">Checking...</p><button id="refresh">Refresh checks</button><table style="margin-top:16px"><tbody id="results"></tbody></table><p><small>Retain troubleshooting table and screenshots for assessment evidence.</small></p></section></main>
<script>async function refresh(){const button=document.getElementById('refresh');button.disabled=true;try{const response=await fetch('api.php',{cache:'no-store'});if(!response.ok)throw Error();const data=await response.json();const body=document.getElementById('results');body.replaceChildren();for(const item of data.checks){const tr=document.createElement('tr'),a=document.createElement('td'),b=document.createElement('td');a.textContent=item.name;b.textContent=data.validation_available?item.status:'Unavailable';b.className=b.textContent.toLowerCase();tr.append(a,b);body.append(tr)}document.getElementById('summary').textContent=data.validation_available?'Live validation results':'Validation unavailable — check AWS access and stack configuration';}catch(e){document.getElementById('summary').textContent='Validation service unavailable'}finally{button.disabled=false}}document.getElementById('refresh').addEventListener('click',refresh);refresh();</script></body></html>
