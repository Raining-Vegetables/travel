<?php
// helpers/meetup.php

class MeetupAPI
{
    private $baseUrl = 'https://api.meetup.com';

    public function getEvents($preferences = [])
    {
        // Using Sydney's largest tech meetup group as an example
        $sydneyGroups = [
            'Sydney-Startups',
            'Sydney-Tech-Startup-Founders',
            'sydneycoders'
        ];

        $allEvents = [];

        foreach ($sydneyGroups as $groupUrlName) {
            $endpoint = "/${groupUrlName}/events";
            $params = [
                'status' => 'upcoming',
                'desc' => 'false',
                'page' => 10
            ];

            $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

            // Debug URL
            error_log("Fetching Meetup events from: " . $url);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SydneyEventsApp/1.0)',
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            error_log("Meetup API response code for $groupUrlName: " . $statusCode);

            if (curl_errno($ch)) {
                error_log("Curl error for $groupUrlName: " . curl_error($ch));
                continue;
            }

            curl_close($ch);

            $events = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($events)) {
                $allEvents = array_merge($allEvents, $events);
            } else {
                error_log("JSON decode error for $groupUrlName: " . json_last_error_msg());
                error_log("Raw response: " . substr($response, 0, 1000));
            }
        }

        return ['events' => $allEvents];
    }

    public function formatEvent($event)
    {
        try {
            // Skip events without essential information
            if (empty($event['name'])) {  // Only check for completely missing name
                error_log('Skipping event due to missing name');
                return null;
            }

            return [
                'name' => $event['name'] ?? 'Unnamed Event',
                'date' => isset($event['local_date']) ? date('M d', strtotime($event['local_date'])) : 'TBA',
                'time' => isset($event['local_time']) ? date('g:i A', strtotime($event['local_time'])) : 'TBA',
                'venue' => isset($event['venue']) ? ($event['venue']['name'] ?? 'Venue TBA') : 'Venue TBA',
                'image' => $event['featured_photo']['photo_link'] ??
                    ($event['group']['group_photo']['photo_link'] ?? null),
                'url' => $event['link'] ?? '#',
                'type' => ($event['group']['name'] ?? 'Meetup') . ' Event',
                'source' => 'meetup',
                'description' => isset($event['description']) ?
                    substr(strip_tags($event['description']), 0, 150) . '...' :
                    'No description available',
                'priceRange' => isset($event['fee']) ?
                    '$' . number_format($event['fee']['amount'], 2) : 'Free'
            ];
        } catch (Exception $e) {
            error_log('Error formatting Meetup event: ' . $e->getMessage());
            error_log('Event data: ' . print_r($event, true));
            return null;
        }
    }
}
