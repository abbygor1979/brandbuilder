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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
		],
	],

    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
    ],
	
    'dalle' => [
        'api_key' => env('DALLE_API_KEY'),
    ],
	
    'pika' => [
        'api_key' => env('PIKA_API_KEY'),
    ],
	
    'domainsdb' => [
        'api_key' => env('DOMAINSDB_API_KEY'),
    ],
	
    'affiliate' => [
        'domain_provider' => env('AFFILIATE_DOMAIN_PROVIDER'),
    ],
	
    'netlify' => [
        'api_key' => env('NETLIFY_API_KEY'),
        'share_endpoint' => 'https://api.netlify.com/api/v1/share',
    ],
	
    'render' => [
        'api_key' => env('RENDER_API_KEY'),
        'share_endpoint' => 'https://api.render.com/v1/share',
    ],
	
    'telegram' => [
        'api_key' => env('TELEGRAM_API_KEY'),
        'share_endpoint' => 'https://api.telegram.org/bot{api_key}/sendMessage',
    ],
	
    'linkedin' => [
        'api_key' => env('LINKEDIN_API_KEY'),
        'share_endpoint' => 'https://api.linkedin.com/v2/shares',
    ],
	
    'x' => [
        'api_key' => env('X_API_KEY'),
        'share_endpoint' => 'https://api.x.com/v1/statuses/update',
    ],
	
    'facebook' => [
        'api_key' => env('FACEBOOK_API_KEY'),
        'share_endpoint' => 'https://graph.facebook.com/v12.0/me/feed',
    ],
];
