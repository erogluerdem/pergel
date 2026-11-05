<?php
$root = realpath(__DIR__ . '/../public');
$dist = $root . '/js/dist';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dist));
$files = [];
foreach($it as $f) if($f->isFile() && preg_match('/\\.js$/',$f->getFilename())) $files[] = $f->getPathname();
$errors = [];
foreach($files as $file){
    $text = file_get_contents($file);
    if(preg_match_all('/(?:from|import\(|export\s+\*\s+from)\s*(?:["\'])([^"\']+)(?:["\'])/m',$text,$m)){
        foreach($m[1] as $path){
            // skip absolute URLs
            if(preg_match('#^https?://#',$path)) continue;
            // resolve absolute starting with /
            if(strpos($path,'/')===0){
                $candidate = $root . $path;
                // try with .js
                if(file_exists($candidate) || file_exists($candidate . '.js') || file_exists(rtrim($candidate,'/') . '/index.js')){
                    // ok
                } else {
                    $errors[] = [ 'file'=>$file, 'ref'=>$path, 'reason'=>'missing'];
                }
            } else {
                // relative to file
                $cand = dirname($file) . '/' . $path;
                if(file_exists($cand) || file_exists($cand . '.js') || file_exists(rtrim($cand,'/') . '/index.js')){
                    // ok
                } else {
                    // try from dist root
                    $cand2 = $dist . '/' . ltrim($path,'./');
                    if(file_exists($cand2) || file_exists($cand2 . '.js') || file_exists(rtrim($cand2,'/') . '/index.js')){
                        // ok
                    } else {
                        $errors[] = [ 'file'=>$file, 'ref'=>$path, 'reason'=>'missing'];
                    }
                }
            }
        }
    }
}
if(empty($errors)) echo "All imports resolved\n"; else {
    foreach($errors as $e) echo "MISSING in {$e['file']} -> {$e['ref']} (reason: {$e['reason']})\n";
}
