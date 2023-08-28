<?php
namespace App\Actions\Alimify\OpenAI;

use Illuminate\Support\Facades\Log;
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

        // $response = OpenAI::chat()->create([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         ['role' => 'user', 'content' => $keyword],
        //     ],
        //     'temperature' => 1,
        //     'max_tokens' => 256,
        // ]);
        // Log::info("open ai response - ", [
        //     "data" => $response
        // ]);
        // $rr = array_map(function($item){
        //     return $item['message']['content'];
        // }, $response['choices']);

        // return str_replace('"',"",join(" ", $rr));
    }
}