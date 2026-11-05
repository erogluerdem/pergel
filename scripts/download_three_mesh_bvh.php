<?php
$u='https://registry.npmjs.org/three-mesh-bvh/-/three-mesh-bvh-0.5.0.tgz';
$o='three-mesh-bvh.tgz';
file_put_contents($o, file_get_contents($u));
echo file_exists($o)?"DL_OK":"DL_FAIL";
