<?php
$c = file_get_contents('http://127.0.0.1:8000/portfolio/projects/7');
if (strpos($c, 'font-awesome') !== false) echo "FOUND font-awesome\n";
if (strpos($c, '5.13.0') !== false) echo "FOUND FA 5.13.0\n";
if (strpos($c, '6.4.0') !== false) echo "FOUND FA 6.4.0\n";
