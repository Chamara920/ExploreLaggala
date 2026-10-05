<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Destination;
use App\Models\MobileCoverageReport;

$dests = Destination::with('translations')->get()->map(function($d) {
    return [
        'id' => $d->id,
        'en' => $d->translations->firstWhere('locale', 'en')?->name,
        'si' => $d->translations->firstWhere('locale', 'si')?->name,
        'ta' => $d->translations->firstWhere('locale', 'ta')?->name,
        'lat' => (float)$d->latitude,
        'lng' => (float)$d->longitude,
    ];
});
print_r($dests->toArray());

$reports = MobileCoverageReport::with('translations')->get()->map(function($r) {
    return [
        'id' => $r->id,
        'location_name' => $r->location_name,
        'op' => $r->network_operator,
        'type' => $r->coverage_type,
        'signal' => $r->signal_strength,
        'status' => $r->status,
        'lat' => $r->latitude,
        'lng' => $r->longitude,
    ];
});
print_r($reports->toArray());
