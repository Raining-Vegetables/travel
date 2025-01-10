<?php
// helpers/sydneyevents.php

class SydneyEventsAPI
{
    private $baseUrl = 'https://api.destinationnsw.com.au/v1';
    private $apiKey = 'dnsw_api_578'; // Public API key for testing

    public function getEvents($preferences = [])
    {
        $endpoint = '/products';

        $params = [
            'type' => 'EVENT',
            'limit' => 20,
            'region' => 'sydney',
            'fromDate' => date('Y-m-d'),
            'api_key' => $this->apiKey
        ];

        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);
        error_log("Sydney Events API URL: " . $url);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        error_log("Sydney Events API Status Code: " . $statusCode);

        if (curl_errno($ch)) {
            error_log("Sydney Events API error: " . curl_error($ch));
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            return null;
        }

        return $decoded;
    }

    public function formatEvent($event)
    {
        if (empty($event['name'])) {
            return null;
        }

        return [
            'name' => $event['name'],
            'date' => date('M d', strtotime($event['startDate'])),
            'time' => date('g:i A', strtotime($event['startDate'])),
            'venue' => $event['location']['name'] ?? 'Sydney',
            'image' => $event['images'][0]['url'] ?? null,
            'url' => $event['bookingUrl'] ?? $event['websiteUrl'] ?? '#',
            'type' => $event['category'] ?? 'Local Event',
            'source' => 'sydney.com',
            'description' => isset($event['description']) ?
                substr(strip_tags($event['description']), 0, 150) . '...' :
                'No description available',
            'priceRange' => isset($event['pricing']) ? $event['pricing'] : 'Contact for pricing'
        ];
    }
}
