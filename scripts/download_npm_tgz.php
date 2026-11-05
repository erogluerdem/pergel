<?php
$u = $argv[1] ?? die("Usage: php download_npm_tgz.php <url>\n");
$out = $argv[2] ?? 'c:/ikon/tmp/package.tgz';
@mkdir(dirname($out), 0755, true);
$ctx = stream_context_create(['http'=>['timeout'=>60]]);
echo "Downloading $u -> $out\n";
if(!copy($u, $out)){
  echo "Failed to download $u\n"; exit(1);
}
echo "Downloaded\n";
