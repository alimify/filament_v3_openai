<?php

namespace App\Jobs;

use App\Actions\Alimify\OpenAI\GenerateImage;
use App\Actions\Alimify\OpenAI\GeneratePrompt;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $image;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Image $image,
    )
    {
        $this->image  = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(
        GeneratePrompt $prompt,
        GenerateImage $image
        ): void
    {
        Log::info("started - ". $this->image->id);
        $this->image->status = 'processing';
        $this->image->progress = 25;
        $this->image->save();
        $prompt = $prompt->generate($this->image->keyword);
        $this->image->prompt = Str::of($prompt)->trim();
        $this->image->progress = 50;
        $this->image->save();
        Log::info("prompt - " . $this->image->prompt);
        
        $image_url = $image->generate($prompt);
        $path = "images/". Str::slug($this->image->keyword). uniqid() . ".jpg";
        $file_contents = Http::timeout(300)->get($image_url);
        Storage::disk('public')->put($path, $file_contents);
        $this->image->path = $path;
        $this->image->progress = 100;
        $this->image->status = 'completed';
        $this->image->updated_at = Carbon::now();
        $this->image->save();
        Log::info("completed");
    }
}
