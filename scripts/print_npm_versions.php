<?php
$url = $argv[1] ?? die("Usage: php print_npm_versions.php <package>\n");
$j = @file_get_contents("https://registry.npmjs.org/" . $url);
if(!$j){ echo "Failed to fetch registry for $url\n"; exit(1); }
$o = json_decode($j, true);
if(!$o || !isset($o['versions'])){ echo "No versions found\n"; exit(1); }
$keys = array_keys($o['versions']);
$last = array_slice($keys, -10);
echo implode("\n", $last) . "\n";
