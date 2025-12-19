<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'round' => $this->round_number,
            'title' => $this->title ?? 'Race Session',
            'track_name' => $this->track->name,
            'country_flag' => $this->track->country_code, // Para que Android ponga la banderita
            'date' => $this->race_date->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'image' => $this->track->layout_image_url ? asset('storage/' . $this->track->layout_image_url) : null,
            // Si la carrera ha terminado, mandamos el ganador
            'winner' => $this->when($this->status === 'completed', function() {
                $winnerResult = $this->results->where('position', 1)->first();
                return $winnerResult ? $winnerResult->driver->name : null;
            }),
        ];
    }
}