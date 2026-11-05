<?php
$p=__DIR__.'/../storage/app/public/qhse';
if(!is_dir($p)){
    mkdir($p,0755,true);
    echo "created\n";
} else {
    echo "exists\n";
}
file_put_contents($p.'/.touch','ok');
echo file_exists($p.'/.touch')?"writable\n":"not-writable\n";
