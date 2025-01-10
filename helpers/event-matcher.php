<?php
class EventMatcher
{
    private $preferences;

    public function __construct()
    {
        $this->loadUserPreferences();
    }

    private function loadUserPreferences()
    {
        $this->preferences = [
            'visitor_type' => $_COOKIE['visitor-type'] ?? null,
            'duration' => $_COOKIE['stay-duration'] ?? null,
            'arrival_date' => $_COOKIE['arrival-date'] ?? null,
            'interests' => json_decode($_COOKIE['interests'] ?? '[]', true),
            'newcomer_events' => json_decode($_COOKIE['newcomer-events'] ?? '[]', true),
            'location' => $_COOKIE['location'] ?? null,
            'transport' => json_decode($_COOKIE['transport'] ?? '[]', true),
            'range' => $_COOKIE['range'] ?? 'medium',
            'budget' => $_COOKIE['budget'] ?? 'medium'
        ];
    }

    public function filterEvents($events)
    {
        if (empty($events)) return [];

        $filteredEvents = array_filter($events, function ($event) {
            // Date filtering
            if (
                $this->preferences['visitor_type'] === 'planning' &&
                $this->preferences['arrival_date']
            ) {
                $eventDate = strtotime($event['date']);
                $arrivalDate = strtotime($this->preferences['arrival_date']);
                $stayEndDate = $this->getStayEndDate($arrivalDate);

                if ($eventDate < $arrivalDate || $eventDate > $stayEndDate) {
                    return false;
                }
            }

            // Interest matching
            if (!empty($this->preferences['interests'])) {
                $matchesInterest = false;
                foreach ($this->preferences['interests'] as $interest) {
                    if ($this->eventMatchesInterest($event, $interest)) {
                        $matchesInterest = true;
                        break;
                    }
                }
                if (!$matchesInterest) return false;
            }

            // Budget filtering
            if (!$this->eventMatchesBudget($event)) {
                return false;
            }

            // Location filtering
            if (!$this->eventMatchesLocation($event)) {
                return false;
            }

            return true;
        });

        return $this->prioritizeEvents(array_values($filteredEvents));
    }

    private function getStayEndDate($arrivalTimestamp)
    {
        $duration = $this->preferences['duration'];
        switch ($duration) {
            case 'few_days':
                return strtotime('+3 days', $arrivalTimestamp);
            case 'week':
                return strtotime('+7 days', $arrivalTimestamp);
            case 'long':
                return strtotime('+14 days', $arrivalTimestamp);
            default:
                return strtotime('+7 days', $arrivalTimestamp);
        }
    }

    private function eventMatchesInterest($event, $interest)
    {
        $interestMappings = [
            'markets' => ['market', 'food', 'shopping', 'cuisine'],
            'beach' => ['beach', 'coastal', 'surf', 'swimming'],
            'festivals' => ['festival', 'celebration', 'cultural'],
            'arts' => ['art', 'exhibition', 'gallery', 'museum', 'theatre', 'opera'],
            'outdoor' => ['outdoor', 'park', 'garden', 'walk', 'hike'],
            'sports' => ['sport', 'game', 'match', 'racing', 'competition']
        ];

        $keywords = $interestMappings[$interest] ?? [];
        foreach ($keywords as $keyword) {
            if (
                stripos($event['name'], $keyword) !== false ||
                stripos($event['description'], $keyword) !== false ||
                stripos($event['type'], $keyword) !== false
            ) {
                return true;
            }
        }

        return false;
    }

    private function eventMatchesBudget($event)
    {
        if (!isset($event['priceRange'])) return true;

        $budget = $this->preferences['budget'];
        $price = strtolower($event['priceRange']);

        switch ($budget) {
            case 'low':
                return stripos($price, 'free') !== false ||
                    stripos($price, '$') === false ||
                    substr_count($price, '$') === 1;
            case 'medium':
                return substr_count($price, '$') <= 2;
            case 'high':
                return true;
            default:
                return true;
        }
    }

    private function eventMatchesLocation($event)
    {
        if (!$this->preferences['location']) return true;

        $locationMappings = [
            'cbd' => ['sydney cbd', 'city', 'surry hills', 'darlinghurst'],
            'eastern' => ['bondi', 'coogee', 'bronte', 'randwick'],
            'inner-west' => ['newtown', 'marrickville', 'glebe'],
            'north-shore' => ['chatswood', 'north sydney'],
            'northern-beaches' => ['manly', 'dee why', 'mona vale']
        ];

        $locations = $locationMappings[$this->preferences['location']] ?? [];
        foreach ($locations as $loc) {
            if (stripos($event['venue'], $loc) !== false) {
                return true;
            }
        }

        return $this->preferences['range'] === 'anywhere';
    }

    private function prioritizeEvents($events)
    {
        usort($events, function ($a, $b) {
            $scoreA = $this->calculateEventScore($a);
            $scoreB = $this->calculateEventScore($b);

            if ($scoreA === $scoreB) {
                // If scores are equal, sort by date
                return strtotime($a['date']) - strtotime($b['date']);
            }

            return $scoreB - $scoreA;
        });

        return $events;
    }

    private function calculateEventScore($event)
    {
        $score = 0;

        // Boost score for newcomer-friendly events if user selected newcomer events
        if (
            in_array('meetups', $this->preferences['newcomer_events']) &&
            stripos($event['description'], 'newcomer') !== false
        ) {
            $score += 5;
        }

        // Boost score for guided tours if selected
        if (
            in_array('tours', $this->preferences['newcomer_events']) &&
            stripos($event['description'], 'tour') !== false
        ) {
            $score += 5;
        }

        // Boost score for exact interest matches
        foreach ($this->preferences['interests'] as $interest) {
            if ($this->eventMatchesInterest($event, $interest)) {
                $score += 3;
            }
        }

        // Boost score for events in preferred location
        if ($this->eventMatchesLocation($event)) {
            $score += 2;
        }

        return $score;
    }
}
