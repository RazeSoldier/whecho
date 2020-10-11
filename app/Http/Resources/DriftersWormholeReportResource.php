<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriftersWormholeReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'signature_name' => $this->signature_name,
            'submitter' => $this->submitter,
            'submit_time' => $this->created_at->addHours(8)->__toString(),
            'notice' => $this->notice,
        ];
    }
}
