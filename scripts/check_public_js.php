<?php
$url = $argv[1] ?? 'http://127.0.0.1:8000/js/web-ifc-viewer.es.js';
$ctx = stream_context_create(['http'=>['timeout'=>10]]);
$h = @get_headers($url,1,$ctx);
if(!$h){ echo "FAILED_TO_FETCH\n"; exit(1);} 
echo $h[0]."\n";
if(isset($h['Content-Type'])) echo "Content-Type: ".(is_array($h['Content-Type'])?implode(';',$h['Content-Type']):$h['Content-Type'])."\n";
if(isset($h['Content-Length'])) echo "Content-Length: ".(is_array($h['Content-Length'])?implode(';',$h['Content-Length']):$h['Content-Length'])."\n";
