<?php
// Usage: php download_signed.php "http://127.0.0.1:8000/...."
if ($argc < 2) {
    fwrite(STDERR, "Usage: php download_signed.php <url>\n");
    exit(2);
}
$url = $argv[1];
$out = __DIR__ . '/../tmp_ifc_download.ifc';
$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: PHP-Downloader/1.0\r\n",
        'timeout' => 10,
    ],
];
$ctx = stream_context_create($opts);
$data = @file_get_contents($url, false, $ctx);
if ($data === false) {
    fwrite(STDERR, "Failed to download URL: $url\n");
    // print http response headers if available
    if (isset($http_response_header)) {
        fwrite(STDERR, implode("\n", $http_response_header) . "\n");
    }
    exit(3);
}
file_put_contents($out, $data);
$size = filesize($out);
echo "Saved to: $out\n";
echo "Size: $size bytes\n";
foreach ($http_response_header as $h) echo "H: $h\n";
