<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\TagAccountResource;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,

            "mobile_no" => $this->mobile_no,

            "order_type" => [
                "id" => $this->order_type_id,
                "code" => $this->order_type_code,
                "description" => $this->order_type_desc,
            ],

            "account" => [
                "code" => $this->account_code,
                "name" => $this->account_name,
            ],
            "company" => [
                "id" => $this->company_id,
                "code" => $this->company_code,
                "name" => $this->company,
            ],
            "department" => [
                "id" => $this->department_id,
                "code" => $this->department_code,
                "name" => $this->department,
            ],
            "location" => [
                "id" => $this->location_id,
                "code" => $this->location_code,
                "name" => $this->location,
            ],
            "username" => $this->username,
            "role" => new RoleResource($this->role),
            "scope_approval" => TagAccountResource::collection($this->scope_approval),
            "scope_order" => TagAccountResource::collection($this->scope_order),
            "updated_at" => $this->updated_at,
        ];
    }
}
