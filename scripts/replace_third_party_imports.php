<?php
$root = __DIR__ . '/../public/js/dist';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach($files as $f){
    if(!$f->isFile()) continue;
    $path = $f->getPathname();
    if(!preg_match('/\\.js$/',$path)) continue;
    $s = file_get_contents($path);
    $orig = $s;
    $s = str_replace("from 'three'","from '/js/three.module.js'",$s);
    $s = str_replace('from "three"','from "/js/three.module.js"',$s);
    $s = str_replace("from 'three-mesh-bvh'","from '/js/three-mesh-bvh.module.js'",$s);
    $s = str_replace('from "three-mesh-bvh"','from "/js/three-mesh-bvh.module.js"',$s);
    if($s !== $orig) file_put_contents($path,$s) && print("Patched: $path\n");
}
echo "Done\n";
