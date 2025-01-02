<?php
require_once __DIR__ . '/../vendor/autoload.php';


class AiService
{
    private $openai;
    private $model = "gpt-3.5-turbo";

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $this->openai = OpenAI::client($_ENV['OPENAI_API_KEY']);
    }

    public function getRecommendation($userPreferences)
    {
        try {
            // Create a detailed prompt for plan recommendation
            $prompt = $this->createRecommendationPrompt($userPreferences);

            $response = $this->openai->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a Sydney phone plan expert. Analyze user requirements and recommend specific plans from major carriers (Telstra, Optus, Vodafone) and their MVNOs. Provide recommendations in a structured JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            return $this->parseRecommendation($response);
        } catch (Exception $e) {
            error_log("AI Recommendation Error: " . $e->getMessage());
            return null;
        }
    }

    private function createRecommendationPrompt($userPreferences)
    {
        // Convert user preferences into a detailed prompt
        $locationContext = $this->getLocationContext($userPreferences['area']);
        $usageContext = $this->getUsageContext($userPreferences['usage_type']);
        $durationContext = $this->getDurationContext($userPreferences['duration_days']);

        return <<<EOT
Please recommend phone plans for a tourist in Sydney with the following requirements:

Location: {$locationContext}
Usage Pattern: {$usageContext}
Stay Duration: {$durationContext}

Provide three plan recommendations in the following structure:
{
    "recommended": {
        "carrier": "carrier name",
        "plan_name": "specific plan name",
        "data_amount": "amount in GB or unlimited",
        "price": price in AUD,
        "reasoning": ["detailed reasons for recommendation"]
    },
    "budget": {
        // same structure as recommended
    },
    "premium": {
        // same structure as recommended
    }
}

Consider the following when making recommendations:
1. Network coverage in the specified area
2. Tourist-friendly features (easy activation, English support)
3. Value for money based on duration
4. Special requirements for the area
EOT;
    }

    private function parseRecommendation($response)
    {
        $content = $response->choices[0]->message->content;

        // Extract JSON from response
        preg_match('/{.*}/s', $content, $matches);
        if (empty($matches)) {
            throw new Exception("Invalid AI response format");
        }

        $recommendations = json_decode($matches[0], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to parse AI recommendations");
        }

        return $recommendations;
    }

    private function getLocationContext($area)
    {
        $contexts = [
            'eastern' => 'Eastern Suburbs including Bondi Beach area, requiring strong coastal coverage',
            'city' => 'Sydney CBD and surroundings, needing reliable coverage in high-density areas',
            'northern' => 'Northern Beaches area, requiring coverage across beaches and suburban areas',
            'western' => 'Western Sydney region, needing broad suburban coverage',
            'southern' => 'Southern Sydney area, requiring coverage across residential and coastal areas'
        ];

        return $contexts[$area] ?? 'Sydney metropolitan area';
    }

    private function getUsageContext($usageType)
    {
        $contexts = [
            'basic' => 'Basic usage for maps and messaging, approximately 1GB/week',
            'regular' => 'Regular social media and video calls, approximately 3GB/week',
            'heavy' => 'Heavy usage including streaming and work, 5GB+/week'
        ];

        return $contexts[$usageType] ?? 'Regular usage patterns';
    }

    private function getDurationContext($duration)
    {
        $contexts = [
            'short' => 'Short stay (less than 2 weeks)',
            'medium' => 'Medium stay (2-4 weeks)',
            'long' => 'Extended stay (more than a month)'
        ];

        return $contexts[$duration] ?? 'Medium stay';
    }
}
