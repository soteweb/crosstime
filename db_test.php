<?php

use App\Models\EventType;
use Illuminate\Support\Str;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to create EventType...\n";
    $name = "Test Category " . time();
    $slug = Str::slug($name);
    $color = "#ff0000";

    $type = EventType::create([
        'name' => $name,
        'slug' => $slug,
        'color' => $color,
    ]);

    echo "Success! Created ID: " . $type->id . "\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
