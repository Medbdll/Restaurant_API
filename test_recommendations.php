<?php

// Test script for recommendation API
$ch = curl_init();

echo "=== Testing Recommendation API ===\n\n";

// Step 1: Login to get token
echo "1. Logging in...\n";
$loginData = json_encode([
    'email' => 'test@example.com',
    'password' => 'password'
]);

curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $loginData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$loginResponse = curl_exec($ch);
$loginData = json_decode($loginResponse, true);
$token = $loginData['token'] ?? null;

if (!$token) {
    echo "ERROR: Login failed - " . ($loginResponse ?? 'No response') . "\n";
    exit(1);
}

echo "SUCCESS: Got token\n";

// Step 2: Test POST to create recommendation
echo "\n2. Testing POST /api/recommendations/1 (create recommendation)...\n";
$recommendData = json_encode([
    'plat_id' => 1
]);

curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/recommendations/1');
curl_setopt($ch, CURLOPT_POSTFIELDS, $recommendData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);

$postResponse = curl_exec($ch);
echo "POST Response: " . $postResponse . "\n";

$postData = json_decode($postResponse, true);
if ($postData && isset($postData['recommendation_id'])) {
    echo "SUCCESS: Recommendation created with ID {$postData['recommendation_id']}\n";
    $recommendationId = $postData['recommendation_id'];
} else {
    echo "ERROR: POST request failed\n";
    $recommendationId = null;
}

// Step 3: Test GET to retrieve recommendations
echo "\n3. Testing GET /api/recommendations/1 (retrieve recommendations)...\n";
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/recommendations/1');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, null);

$getResponse = curl_exec($ch);
echo "GET Response: " . $getResponse . "\n";

$getData = json_decode($getResponse, true);
if ($getData && isset($getData['recommendations'])) {
    echo "SUCCESS: Retrieved {$getData['total_count']} recommendations\n";
    echo "User: {$getData['user']['name']} ({$getData['user']['email']})\n";
    echo "Dietary tags: " . implode(', ', $getData['user']['dietary_tags']) . "\n";
    
    foreach ($getData['recommendations'] as $rec) {
        $status = $rec['status'];
        $score = $rec['score'] ?? 'N/A';
        $recommended = $rec['recommended'] ? 'YES' : 'NO';
        echo "  - Rec {$rec['id']}: {$rec['plat']['name']} | Score: {$score} | Recommended: {$recommended} | Status: {$status}\n";
    }
} else {
    echo "ERROR: GET request failed\n";
}

// Step 4: Compare results
echo "\n4. Comparison:\n";
if ($recommendationId && $getData && isset($getData['recommendations'])) {
    $foundRec = collect($getData['recommendations'])->firstWhere('id', $recommendationId);
    if ($foundRec) {
        echo "✓ Recommendation created via POST was found via GET\n";
        echo "✓ Both methods working correctly\n";
    } else {
        echo "✗ Recommendation created via POST was NOT found via GET\n";
        echo "✗ Database sync issue detected\n";
    }
} else {
    echo "✗ Could not compare - missing data from one or both requests\n";
}

curl_close($ch);
echo "\n=== Test Complete ===\n";
