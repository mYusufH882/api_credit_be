<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'credit_type' => $this->credit_type,
            'name' => $this->name,
            'total_transaction' => $this->total_transaction,
            'tenor' => $this->tenor,
            'total_credit' => $this->total_credit,
            'status' => $this->status,
        ];
    }
}
