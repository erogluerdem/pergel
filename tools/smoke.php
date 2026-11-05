<?php
$paths = ['/apps-contact','/profile','/messages','/notes','/tasks','/activities'];
foreach ($paths as $p) {
    $url = 'http://localhost:8000' . $p;
    $h = @get_headers($url);
    if ($h === false) {
        echo "$p => ERROR (no response)\n";
    } else {
        echo "$p => {$h[0]}\n";
    }
}
