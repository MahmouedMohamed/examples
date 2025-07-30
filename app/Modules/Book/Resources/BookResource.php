<?php

namespace App\Modules\Book\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'publication_date' => $this->publication_date,
            'title' => $this->title,
            'author' => $this->author,
        ];
    }
}
