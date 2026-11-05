<?php
$u='https://registry.npmjs.org/web-ifc-viewer/-/web-ifc-viewer-1.0.218.tgz';
$out='webifc.tgz';
$ctx = stream_context_create(['http'=>['timeout'=>30]]);
$fp = @fopen($u,'r',false,$ctx);
if(!$fp){ echo "FAILED_TO_OPEN\n"; exit(1);} 
file_put_contents($out, stream_get_contents($fp));
echo file_exists($out)?"DOWNLOADED\n":"SAVE_FAILED\n";
