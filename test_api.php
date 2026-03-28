<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Plat;
use App\Models\Recommendation;

// Get test data
$user = User::first();
$plat = Plat::first();

echo "User ID: {$user->id}\n";
echo "Plat ID: {$plat->id}\n";

// Test recommendation creation
$recommendation = Recommendation::updateOrCreate(
    [
        'user_id' => $user->id,
        'plat_id' => $plat->id,
    ],
    [
        'status' => 'processing',
        'score' => null,
        'warning_message' => null,
    ]
);

echo "Recommendation ID: {$recommendation->id}, Status: {$recommendation->status}\n";

// Test update
$updateResult = $recommendation->update([
    'score' => 85,
    'warning_message' => 'Test warning',
    'status' => 'completed',
]);

echo "Update result: " . ($updateResult ? 'success' : 'failed') . "\n";
echo "Final status: {$recommendation->status}, Score: {$recommendation->score}\n";
