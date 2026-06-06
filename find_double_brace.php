<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
use Illuminate\Http\Request;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/kodud/b1-s1', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
$pos = strpos($content, '}}');
if ($pos !== false) {
    echo "Found at: " . $pos . "\n";
    echo substr($content, max(0, $pos - 200), 400) . "\n";
} else {
    echo "Not found\n";
}
