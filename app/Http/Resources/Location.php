<?php

namespace TravelCompanion\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Location extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			"data" => [
				"type"       => "location",
				"id"         => $this->id,
				"attributes" => [
					"is_draft"     => $this->is_draft,
					"coordinates"  => $this->coordinates,
					"name"         => $this->name,
					"info"         => $this->info,
					"published_at" => $this->published_at,
				],
			],
			"links" => [
				"self" => url("/locations/" . $this->id),
			],
		];
	}
}