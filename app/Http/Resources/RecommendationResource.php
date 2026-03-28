<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plate_id' => $this->plat_id,
            'plate_name' => $this->plat?->name,
            'score' => $this->score,
            'label' => $this->label,
            'warning_message' => $this->warning_message,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
