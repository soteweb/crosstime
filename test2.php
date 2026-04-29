<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use App\Models\Event;

foreach(Event::whereIn('id', [1,2,3])->get() as $e) { // Assuming 1,2,3 are the first 3 seeded
    $url = $e->featured_image;
    echo "\nID: " . $e->id . " - " . $e->title . "\nURL: " . ($url ?? 'NULL') . "\n";
    if($url) {
        $headers = @get_headers($url);
        echo "HTTP Status: " . ($headers ? $headers[0] : "FAILED TO CONNECT") . "\n";
    }
}
