<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'huggingface' => [
        'api_key' => env('HUGGINGFACE_API_KEY') ?: env('HF_TOKEN'),
        'api_url' => env('HUGGINGFACE_API_URL', 'https://api-inference.huggingface.co/models/meta-llama/Llama-2-7b-chat-hf'),
        'timeout' => env('HUGGINGFACE_TIMEOUT', 30),
        'retry_attempts' => env('HUGGINGFACE_RETRY_ATTEMPTS', 3),
        'token' => env('HF_TOKEN'), // Keep for backward compatibility
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'api_url' => env('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'),
        'model' => env('GROQ_MODEL', 'llama2-70b-4096'),
        'timeout' => env('GROQ_TIMEOUT', 30),
        'token' => env('GROQ_API_KEY'), // Keep for backward compatibility
    ],
    
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'api_url' => env('ANTHROPIC_API_URL', 'https://api.anthropic.com/v1/messages'),
        'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
        'timeout' => env('ANTHROPIC_TIMEOUT', 30),
    ],

];
