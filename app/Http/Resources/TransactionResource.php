<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;

class TransactionResource extends JsonResource
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
            "order_no" => $this->order_no,
            "reason" => $this->reason,
            "order_type" => $this->order_type,

            "dates" => [
                "date_ordered" => $this->date_ordered,
                "date_needed" => $this->date_needed,
                "date_approved" => $this->date_approved,
                "date_served" => $this->date_served,
                "date_disapproved" => $this->deleted_at,
            ],

            "client" => [
                "id" => $this->client_id,
                "code" => $this->client_code,
                "name" => $this->client_name,
            ],

            "drop" => [
                "id" => $this->drop_id,
                "code" => $this->drop_code,
                "name" => $this->drop_name,
            ],

            "company" => [
                "id" => $this->company_id,
                "code" => $this->company_code,
                "name" => $this->company_name,
            ],
            "department" => [
                "id" => $this->department_id,
                "code" => $this->department_code,
                "name" => $this->department_name,
            ],
            "location" => [
                "id" => $this->location_id,
                "code" => $this->location_code,
                "name" => $this->location_name,
            ],
            "customer" => [
                "id" => $this->customer_id,
                "code" => $this->customer_code,
                "name" => $this->customer_name,
            ],
            "requestor" => [
                "id" => $this->requestor_id,
                "name" => $this->requestor_name,
            ],
            "approver" =>
                $this->approver_id && $this->approver_name
                    ? [
                        "id" => $this->approver_id,
                        "name" => $this->approver_name,
                    ]
                    : null,
            "order" => OrderResource::collection($this->orders),
        ];
    }
}
