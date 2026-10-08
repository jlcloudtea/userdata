<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AWS Lab — EC2 Instance Dashboard</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="row"><div class="col-md-12">
      <?php include('menu.php'); ?>
      <div class="jumbotron">
        <h2>AWS Lab — EC2 Instance Dashboard</h2>
        <p class="text-muted">Instance details and learning environment</p>
        <?php include('get-index-meta-data.php'); ?>
        <hr>
        <?php include('get-cpu-load.php'); ?>
      </div>
    </div></div>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/scripts.js"></script>
</body>
</html>
