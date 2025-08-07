<?php
$lines = file('conf/httpd.conf');

for ($i = 0; $i < count($lines); $i++) {
    if (strpos(strtolower($lines[$i]), 'define srvroot') === 0) {
        $lines[$i] = 'Define SRVROOT "SERVER ROOT FULL PATH"' . "\r\n";
    }
} 

file_put_contents('conf/httpd.conf', implode('', $lines));
?>