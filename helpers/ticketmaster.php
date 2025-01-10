<?php
// helpers/ticketmaster.php

class TicketmasterAPI
{
    private $apiKey;
    private $baseUrl = 'https://app.ticketmaster.com/discovery/v2/';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    // Map our categories to Ticketmaster classifications
    private function mapCategory($category)
    {
        $categoryMap = [
            'arts' => 'Arts & Theatre',
            'festivals' => 'Music',
            'sports' => 'Sports',
            'markets' => 'Miscellaneous'
        ];

        return $categoryMap[$category] ?? null;
    }

    // Get events based on user preferences
    public function getEvents($preferences = [])
    {
        $endpoint = 'events.json';

        // Base parameters
        $params = [
            'apikey' => $this->apiKey,
            'city' => 'Sydney',
            'countryCode' => 'AU',
            'sort' => 'date,asc',
            'size' => 20
        ];

        // Add date filtering if available
        if (isset($preferences['startDate'])) {
            $params['startDateTime'] = $preferences['startDate'] . 'T00:00:00Z';
        }

        // Add category filtering if available
        if (isset($preferences['interests']) && !empty($preferences['interests'])) {
            $categories = array_map([$this, 'mapCategory'], $preferences['interests']);
            $categories = array_filter($categories);
            if (!empty($categories)) {
                $params['classificationName'] = implode(',', $categories);
            }
        }

        // Build query string
        $query = http_build_query($params);
        $url = $this->baseUrl . $endpoint . '?' . $query;

        // Make API request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    // Format event data for display
    public function formatEvent($event)
    {
        return [
            'name' => $event['name'],
            'date' => date('M d', strtotime($event['dates']['start']['localDate'])),
            'time' => isset($event['dates']['start']['localTime'])
                ? date('g:i A', strtotime($event['dates']['start']['localTime']))
                : 'TBA',
            'venue' => $event['_embedded']['venues'][0]['name'] ?? 'Venue TBA',
            'image' => $event['images'][0]['url'] ?? null,
            'url' => $event['url'],
            'type' => $event['classifications'][0]['segment']['name'] ?? 'Event',
            'priceRange' => isset($event['priceRanges'])
                ? '$' . $event['priceRanges'][0]['min'] . ' - $' . $event['priceRanges'][0]['max']
                : 'Price TBA'
        ];
    }
}
