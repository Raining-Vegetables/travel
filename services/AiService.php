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

    public function enhanceRecommendations($planData, $userPreferences)
    {
        try {
            // Create a more structured prompt
            $prompt = $this->createStructuredPrompt($planData, $userPreferences);

            $response = $this->openai->chat()->create([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a Sydney phone plan expert. Provide insights in a structured format using ### as section separators. Each section should start with the category name followed by bullet points.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);

            return $this->parseAiResponse($response);
        } catch (Exception $e) {
            error_log("AI Service Error: " . $e->getMessage());
            return null;
        }
    }

    private function createStructuredPrompt($planData, $userPreferences)
    {
        return <<<EOT
Analyze this Sydney phone plan scenario and provide specific insights:

User Profile:
- Location: {$userPreferences['location']} Sydney
- Usage Type: {$userPreferences['usage_type']}
- Duration: {$userPreferences['duration']} days
- Activities: {$userPreferences['usage_pattern']['activities']}

Recommended Plan:
- Carrier: {$planData['recommended']['carrier_name']}
- Data: {$planData['recommended']['data_amount']}
- Price: \${$planData['recommended']['price']}
- Coverage: {$planData['recommended']['coverage_rating']}/5

Provide insights in the following format:

###LOCATION_TIPS
• Specific tips for {$userPreferences['location']} area
• Network performance in key locations
• Coverage hotspots and dead zones

###USAGE_ADVICE
• Data management tips for {$userPreferences['usage_type']} usage
• App-specific recommendations
• Wi-Fi hotspot locations

###MONEY_SAVING
• Cost-saving strategies
• Plan optimization tips
• Hidden fees to watch for

###TOURIST_WARNINGS
• Common tourist pitfalls
• Important restrictions
• Emergency considerations
EOT;
    }

    private function parseAiResponse($response)
    {
        $content = $response->choices[0]->message->content;

        // Initialize categories
        $insights = [
            'locationTips' => [],
            'usageAdvice' => [],
            'savingTips' => [],
            'warnings' => []
        ];

        // Split content by sections
        $sections = explode('###', $content);

        foreach ($sections as $section) {
            $section = trim($section);
            if (empty($section)) continue;

            // Extract section name and content
            $lines = explode("\n", $section);
            $sectionName = trim(array_shift($lines));

            // Process each bullet point
            $bullets = array_filter(array_map('trim', $lines), function ($line) {
                return !empty($line) && strpos($line, '•') === 0;
            });

            // Map sections to insight categories
            switch ($sectionName) {
                case 'LOCATION_TIPS':
                    $insights['locationTips'] = array_map(function ($bullet) {
                        return trim(substr($bullet, 1));
                    }, $bullets);
                    break;
                case 'USAGE_ADVICE':
                    $insights['usageAdvice'] = array_map(function ($bullet) {
                        return trim(substr($bullet, 1));
                    }, $bullets);
                    break;
                case 'MONEY_SAVING':
                    $insights['savingTips'] = array_map(function ($bullet) {
                        return trim(substr($bullet, 1));
                    }, $bullets);
                    break;
                case 'TOURIST_WARNINGS':
                    $insights['warnings'] = array_map(function ($bullet) {
                        return trim(substr($bullet, 1));
                    }, $bullets);
                    break;
            }
        }

        return $insights;
    }
}
