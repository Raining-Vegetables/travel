<?php
// helpers/eventbrite.php

class EventbriteAPI
{
    private $privateToken;
    private $baseUrl = 'https://www.eventbriteapi.com/v3/';

    public function __construct($privateToken)
    {
        // Remove 'PRIVATE-' prefix if it exists
        $this->privateToken = str_replace('PRIVATE-', '', $privateToken);
    }

    public function getEvents($preferences = [])
    {
        $endpoint = 'events/search/';

        // Base parameters with more specific filters
        $params = [
            'location.address' => 'Sydney,NSW',
            'location.within' => '30km',
            'start_date.range_start' => date('Y-m-d') . 'T00:00:00',
            'expand' => 'venue,ticket_availability',
            'status' => 'live', // Only get live events
            'order_by' => 'start_asc'
        ];

        // Add category filtering if interests are provided
        if (!empty($preferences['interests'])) {
            $params['categories'] = implode(',', $preferences['interests']);
        }

        // Build query string
        $query = http_build_query($params);
        $url = $this->baseUrl . $endpoint . '?' . $query;

        // Debug URL (remove sensitive information)
        error_log('Eventbrite API URL (without token): ' . preg_replace('/token=[^&]*/', 'token=REDACTED', $url));

        // Initialize CURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->privateToken,
                'Accept: application/json'
            ],
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);

        // Enable verbose debugging
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        // Execute request
        $response = curl_exec($ch);
        $err = curl_errno($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Debug information
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("Curl verbose output: " . $verboseLog);
        error_log("HTTP Status Code: " . $statusCode);

        if ($err) {
            error_log("Curl error occurred: " . curl_error($ch));
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        // Handle API response
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg());
            error_log("Raw response: " . substr($response, 0, 1000));
            return null;
        }

        // Check for API error response
        if (isset($decoded['error']) || isset($decoded['error_description'])) {
            error_log("Eventbrite API error: " .
                ($decoded['error_description'] ?? $decoded['error'] ?? 'Unknown error'));
            return null;
        }

        return $decoded;
    }

    public function formatEvent($event)
    {
        try {
            $formattedEvent = [
                'name' => $event['name']['text'] ?? 'Unnamed Event',
                'date' => isset($event['start']['local']) ? date('M d', strtotime($event['start']['local'])) : 'TBA',
                'time' => isset($event['start']['local']) ? date('g:i A', strtotime($event['start']['local'])) : 'TBA',
                'venue' => isset($event['venue']) ? ($event['venue']['name'] ?? 'Venue TBA') : 'Venue TBA',
                'image' => isset($event['logo']) ? ($event['logo']['url'] ?? null) : null,
                'url' => $event['url'] ?? '#',
                'type' => 'Local Event',
                'source' => 'eventbrite',
                'description' => isset($event['description']['text']) ?
                    substr($event['description']['text'], 0, 150) . '...' :
                    'No description available'
            ];

            // Add price information
            if (isset($event['ticket_availability'])) {
                if ($event['is_free']) {
                    $formattedEvent['priceRange'] = 'Free';
                } else {
                    $minPrice = $event['ticket_availability']['minimum_ticket_price']['major_value'] ?? null;
                    $maxPrice = $event['ticket_availability']['maximum_ticket_price']['major_value'] ?? null;
                    if ($minPrice && $maxPrice) {
                        $formattedEvent['priceRange'] = '$' . $minPrice . ' - $' . $maxPrice;
                    } else {
                        $formattedEvent['priceRange'] = 'Paid';
                    }
                }
            } else {
                $formattedEvent['priceRange'] = 'Price TBA';
            }

            return $formattedEvent;
        } catch (Exception $e) {
            error_log('Error formatting Eventbrite event: ' . $e->getMessage());
            error_log('Event data: ' . print_r($event, true));
            return null;
        }
    }
}
