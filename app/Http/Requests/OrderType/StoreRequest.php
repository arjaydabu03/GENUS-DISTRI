<?php

namespace App\Http\Requests\OrderType;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "code" => [
                "required",
                "string",
                $this->route()->order_type
                    ? "unique:order_type,code," . $this->route()->order_type
                    : "unique:order_type,code",
            ],
            "description" => ["required", "string"],
        ];
    }
}
