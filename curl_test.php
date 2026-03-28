<?php

// Simple test to check recommendation API
$ch = curl_init();

// First, create a user
$registerData = json_encode([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123'
]);

curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $registerData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$registerResponse = curl_exec($ch);
echo "Register response: " . $registerResponse . "\n";

// Then login to get token
$loginData = json_encode([
    'email' => 'test@example.com',
    'password' => 'password'
]);

curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/login');
curl_setopt($ch, CURLOPT_POSTFIELDS, $loginData);

$loginResponse = curl_exec($ch);
echo "Raw login response: " . $loginResponse . "\n";
$loginData = json_decode($loginResponse, true);
echo "Parsed login data: "; print_r($loginData); echo "\n";
$token = $loginData['token'] ?? null;

echo "Login token: " . ($token ? 'RECEIVED' : 'MISSING') . "\n";

if ($token) {
    // Test recommendation API
    $recommendData = json_encode([
        'plat_id' => 1
    ]);
    
    curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/recommendations/1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $recommendData);
    
    $recommendResponse = curl_exec($ch);
    echo "Recommendation response: " . $recommendResponse . "\n";
    
    // Try to decode JSON response
    $recommendData = json_decode($recommendResponse, true);
    if ($recommendData) {
        echo "Parsed recommendation data: "; print_r($recommendData); echo "\n";
        if (isset($recommendData['recommendation_id'])) {
            echo "SUCCESS: Recommendation ID {$recommendData['recommendation_id']} was created!\n";
        }
    } else {
        echo "ERROR: Response was not valid JSON\n";
    }
}

curl_close($ch);
