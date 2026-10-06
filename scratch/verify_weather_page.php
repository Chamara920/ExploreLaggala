<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$request = Request::create('/plan/weather-safety?lang=si', 'GET');
$response = $app->handle($request);

$content = $response->getContent();

echo 'Status: '.$response->getStatusCode()."\n";
echo 'Length: '.strlen($content)."\n";
echo 'Has weather-section: '.(str_contains($content, 'id="weather-section"') ? 'YES' : 'NO')."\n";
echo 'Has safety-section: '.(str_contains($content, 'id="safety-section"') ? 'YES' : 'NO')."\n";
echo 'Has station Pallegama: '.(str_contains($content, 'Pallegama') ? 'YES' : 'NO')."\n";
echo 'Has station Riverston: '.(str_contains($content, 'Riverston') ? 'YES' : 'NO')."\n";
echo 'Has guest CTA: '.(str_contains($content, 'Community Updates') ? 'YES' : 'NO')."\n";
echo 'Has Emergency DMC 117: '.(str_contains($content, '117') ? 'YES' : 'NO')."\n";
echo 'Has Landslide Alert: '.(str_contains($content, 'නායයෑම්') || str_contains($content, 'Landslide') ? 'YES' : 'NO')."\n";
