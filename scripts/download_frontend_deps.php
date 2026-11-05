<?php
$files = [
  // three examples
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/controls/OrbitControls.js','dest'=>'public/js/three/examples/jsm/controls/OrbitControls.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/renderers/CSS2DRenderer.js','dest'=>'public/js/three/examples/jsm/renderers/CSS2DRenderer.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/utils/BufferGeometryUtils.js','dest'=>'public/js/three/examples/jsm/utils/BufferGeometryUtils.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/loaders/GLTFLoader.js','dest'=>'public/js/three/examples/jsm/loaders/GLTFLoader.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/exporters/GLTFExporter.js','dest'=>'public/js/three/examples/jsm/exporters/GLTFExporter.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/postprocessing/EffectComposer.js','dest'=>'public/js/three/examples/jsm/postprocessing/EffectComposer.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/postprocessing/RenderPass.js','dest'=>'public/js/three/examples/jsm/postprocessing/RenderPass.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/postprocessing/ShaderPass.js','dest'=>'public/js/three/examples/jsm/postprocessing/ShaderPass.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/postprocessing/SAOPass.js','dest'=>'public/js/three/examples/jsm/postprocessing/SAOPass.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/shaders/FXAAShader.js','dest'=>'public/js/three/examples/jsm/shaders/FXAAShader.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/lines/LineSegmentsGeometry.js','dest'=>'public/js/three/examples/jsm/lines/LineSegmentsGeometry.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/lines/LineSegments2.js','dest'=>'public/js/three/examples/jsm/lines/LineSegments2.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/lines/LineMaterial.js','dest'=>'public/js/three/examples/jsm/lines/LineMaterial.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/shaders/HorizontalBlurShader.js','dest'=>'public/js/three/examples/jsm/shaders/HorizontalBlurShader.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/three@0.161.0/examples/jsm/shaders/VerticalBlurShader.js','dest'=>'public/js/three/examples/jsm/shaders/VerticalBlurShader.js'],
  // utilities
  ['url'=>'https://cdn.jsdelivr.net/npm/gsap@3.12.2/index.js','dest'=>'public/js/gsap.module.js'],
  // camera-controls (ESM build)
  ['url'=>'https://cdn.jsdelivr.net/npm/camera-controls@4.0.0/dist/camera-controls.module.js','dest'=>'public/js/camera-controls.module.js'],
  // web-ifc and web-ifc-three (try to pull wrapper files)
  ['url'=>'https://cdn.jsdelivr.net/npm/web-ifc@0.0.34/web-ifc.wasm','dest'=>'public/js/web-ifc.wasm'],
  ['url'=>'https://cdn.jsdelivr.net/npm/web-ifc@0.0.34/web-ifc.js','dest'=>'public/js/web-ifc.js'],
  ['url'=>'https://cdn.jsdelivr.net/npm/web-ifc-three@0.0.5/dist/IFCLoader.js','dest'=>'public/js/web-ifc-three/IFCLoader.js']
];

foreach($files as $f){
    $url = $f['url']; $dest = __DIR__ . '/..'.'/'.$f['dest'];
    $dir = dirname($dest);
    if(!is_dir($dir)) mkdir($dir, 0755, true);
    echo "Fetching $url -> $dest\n";
    $ctx = stream_context_create(['http'=>['timeout'=>30]]);
    $data = @file_get_contents($url, false, $ctx);
    if($data === false){ echo "FAILED: $url\n"; continue; }
    file_put_contents($dest, $data);
    echo "OK\n";
}

echo "Done\n";
