<?php
namespace App\Actions\Alimify\OpenAI;
use OpenAI\Laravel\Facades\OpenAI;

class GeneratePrompt {

    public function generate( string $keyword ): string
    {
        $keyword = 'Write a 50 word prompt that will be used to generate an AI image. The image is about: '. $keyword;
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $keyword,
            'temperature' => 0.8,
            'max_tokens' => 30,
        ]);

        return $result['choices'][0]['text'];
    }
}