<?php
$t = $_POST['t'];
$m = $_POST['m'];

$date = date('Y-m-d h:i:s');
$log_msg = $date.'  |  '.$t.'  |  '.$m;

error_log($log_msg.PHP_EOL, 3, './log/book.log');
