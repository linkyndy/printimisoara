<?php
date_default_timezone_set('Europe/Bucharest');

echo date('H:i', strtotime('midnight -3 minutes'));
echo "<hr>";
echo date("d.m H:i", time());
echo '<br>';
echo date("d.m H:i", strtotime(date("H:i", time()).'-10minutes'));

?>