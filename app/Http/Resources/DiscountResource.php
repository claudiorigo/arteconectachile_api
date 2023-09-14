<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'type_discount' => $this->resource->type_discount,
            'discount' => $this->resource->discount,
            'state' => $this->resource->state,
            'start_date' => Carbon::parse($this->resource->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($this->resource->end_date)->format('Y-m-d'),
            'type' => $this->resource->type,
            'products' => $this->resource->discountProducts,
            'categories' => $this->resource->discountCategories,
        ];
    }
}
