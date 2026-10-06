<?php

use App\Models\Destination;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$d = Destination::where('status', 'published')->first();
if ($d) {
    echo 'Destination ID: '.$d->id."\n";
    $tr = $d->translationFor(app()->getLocale());
    echo 'Locale: '.app()->getLocale()."\n";
    echo 'Translation found: '.($tr ? 'YES' : 'NO')."\n";
    if ($tr) {
        echo 'Name: '.$tr->name."\n";
        echo 'Slug: '.$tr->slug."\n";
    } else {
        echo "All translations:\n";
        foreach ($d->translations as $t) {
            echo ' - Locale: '.$t->locale.', Name: '.$t->name.', Slug: '.$t->slug."\n";
        }
    }
} else {
    echo "No published destination found.\n";
}
