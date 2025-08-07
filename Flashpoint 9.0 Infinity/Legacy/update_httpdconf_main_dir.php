<?php
if (!file_exists('logs') || !is_dir('logs')) {
    mkdir('logs');
}

$lines = file('conf/httpd.conf');

for ($i = 0; $i < count($lines); $i++) {
    if (strpos(strtolower($lines[$i]), 'define srvroot') === 0) {
        $lines[$i] = 'Define SRVROOT "' . getcwd() . '"' . "\r\n";
    }
} 

file_put_contents('conf/httpd.conf', implode('', $lines));
?>