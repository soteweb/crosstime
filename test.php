<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\Event;

foreach(Event::all() as $e) {
    echo $e->id . ' - ' . $e->title . ' => ' . ($e->featured_image ?? 'NULL') . "\n";
}
