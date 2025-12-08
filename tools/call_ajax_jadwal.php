<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\AjaxController;
$c = new AjaxController();
$res = $c->jadwalByPoli(1);
// $res is Illuminate\Http\JsonResponse
echo $res->getContent();
