<?php
require 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get API key from environment variable
$apiKey = $_ENV['OPENAI_API_KEY'];

// Create a new client with your API key
$client = OpenAI::client($apiKey);

try {
    // Make a simple test request
    $result = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'Say "API connection successful!"'],
        ],
    ]);

    // If successful, print the response
    echo "API Test Result: " . $result->choices[0]->message->content;
} catch (Exception $e) {
    // If there's an error, print it
    echo "Error: " . $e->getMessage();
}
