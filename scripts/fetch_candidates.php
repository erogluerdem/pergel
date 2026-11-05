<?php
$candidates = [
  // camera-controls candidates
  'https://cdn.jsdelivr.net/npm/camera-controls/dist/camera-controls.module.js',
  'https://unpkg.com/camera-controls/dist/camera-controls.module.js',
  'https://cdn.jsdelivr.net/npm/camera-controls@4.0.0/dist/camera-controls.module.js',
  'https://cdn.jsdelivr.net/npm/camera-controls@2.0.8/dist/camera-controls.module.js',
  // web-ifc candidates
  'https://cdn.jsdelivr.net/npm/web-ifc@0.0.38/web-ifc.js',
  'https://unpkg.com/web-ifc@0.0.38/web-ifc.js',
  'https://cdn.jsdelivr.net/npm/web-ifc@0.0.34/web-ifc.js',
  'https://unpkg.com/web-ifc@0.0.34/web-ifc.js',
  // web-ifc-three IFCLoader
  'https://cdn.jsdelivr.net/npm/web-ifc-three/dist/IFCLoader.js',
  'https://unpkg.com/web-ifc-three/dist/IFCLoader.js',
  'https://cdn.jsdelivr.net/npm/web-ifc-three@0.0.5/dist/IFCLoader.js',
];
$root = realpath(__DIR__ . '/../public');
foreach($candidates as $url){
  $path = parse_url($url, PHP_URL_PATH);
  $name = basename($path);
  $dest = $root . '/js/' . $name;
  echo "Trying $url -> $dest\n";
  $ctx = stream_context_create(['http'=>['timeout'=>20]]);
  $data = @file_get_contents($url, false, $ctx);
  if($data === false){ echo "  FAILED\n"; continue; }
  file_put_contents($dest, $data);
  echo "  OK\n";
}
echo "done\n";
