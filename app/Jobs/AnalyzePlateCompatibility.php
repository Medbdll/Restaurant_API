<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Plat;
use App\Models\Recommendation;
use App\Services\RecommendationScoringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzePlateCompatibility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $plate;
    protected $recommendation;

    public function __construct(User $user, Plat $plate, Recommendation $recommendation)
    {
        $this->user = $user;
        $this->plate = $plate;
        $this->recommendation = $recommendation;
        $this->onQueue('recommendations');
    }

    public function handle(RecommendationScoringService $scoringService): void
    {
        try {
            // Get the analysis result
            $result = $scoringService->analyze($this->user, $this->plate);

            // Update the recommendation
            $this->recommendation->update([
                'score' => $result['score'],
                'label' => $result['label'],
                'warning_message' => $result['warning_message'],
                'status' => 'ready'
            ]);

        } catch (\Exception $e) {
            // Mark as failed if something goes wrong
            $this->recommendation->update([
                'status' => 'failed',
                'warning_message' => 'Analysis failed'
            ]);
        }
    }
}
