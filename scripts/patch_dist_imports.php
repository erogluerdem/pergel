<?php
$root = __DIR__ . '/../public/js/dist';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$jsFiles = [];
foreach($files as $f){
    if($f->isFile() && preg_match('/\.js$/',$f->getFilename())) $jsFiles[] = $f->getPathname();
}

foreach($jsFiles as $file){
    $orig = file_get_contents($file);
    $fixed = preg_replace_callback(
        '/(from|export\s+\*\s+from)\s*(\([\'\"]?)([^\)\'\"]+)([\'\"]?)/m',
        function($m) use ($file, $root){
            // m[3] is the path
            $path = $m[3];
            // only touch relative paths
            if(!preg_match('/^\.\.?\//',$path)) return $m[0];
            // ignore URLs
            if(preg_match('#^https?://#',$path)) return $m[0];
            $baseDir = dirname($file);
            $cand1 = realpath($baseDir . '/' . $path . '.js');
            if($cand1 && strpos($cand1, realpath($root)) === 0){
                $new = $path . '.js';
                return str_replace($m[3], $new, $m[0]);
            }
            $cand2 = realpath($baseDir . '/' . $path . '/index.js');
            if($cand2 && strpos($cand2, realpath($root)) === 0){
                $new = $path . '/index.js';
                return str_replace($m[3], $new, $m[0]);
            }
            // Sometimes path already has extension
            if(preg_match('/\.(js|mjs)$/',$path)) return $m[0];
            // no resolution found, leave unchanged
            return $m[0];
        },
        $orig
    );
    if($fixed !== $orig){
        file_put_contents($file, $fixed);
        echo "Patched: $file\n";
    }
}
echo "Done\n";
