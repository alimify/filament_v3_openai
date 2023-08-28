<?php
namespace App\Actions\Alimify\OpenAI;

use OpenAI\Laravel\Facades\OpenAI;

class GenerateImage {

    public function generate( string $prompt): string
    {
        $response =  OpenAI::images()->create([
            'prompt' => $prompt,
            'n' => 1,
            'size' => '512x512',
            'response_format' => 'url',
        ]);
        return $response['data'][0]['url'];
    }

}