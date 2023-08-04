<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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

                $this->route()->id
                    ? "unique:users,account_code," . $this->route()->id
                    : "unique:users,account_code",
            ],
            "name" => "required|string",
            "order_type.id" => "required",
            "order_type.code" => "required",
            "order_type.desc" => "required",
            "location.id" => "required",
            "location.code" => "required",
            "location.name" => "required",
            "department.id" => "required",
            "department.code" => "required",
            "department.name" => "required",
            "company.id" => "required",
            "company.code" => "required",
            "company.name" => "required",
            "scope_order" => ["required_if:role_id,2", "array"],
            "role_id" => "required|exists:role,id,deleted_at,NULL",
            "mobile_no" => [
                "required",
                "regex:[63]",
                "digits:12",

                $this->route()->id
                    ? "unique:users,mobile_no," . $this->route()->id
                    : "unique:users,mobile_no",
            ],
            "username" => [
                "required",
                "string",
                $this->route()->id
                    ? "unique:users,username," . $this->route()->id
                    : "unique:users,username",
            ],
        ];
    }

    public function attributes()
    {
        return [
            "scope_approval" => "scope for approval",
            "scope_order" => "scope for ordering",
            "mobile_no" => "mobile no",
        ];
    }

    public function messages()
    {
        return [
            "required_if" => "The :attribute field is required.",
            "exists" => "Role is not Registered",
            "regex" => "The :attribute format must start with 63.",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // $validator->errors()->add("custom", "STOP!");
            // $validator->errors()->add("custom", $this->route()->id);

            // $user_permission = Auth()->user()->role->access_permission;
            // $user_role = explode(", ", $user_permission);
            // $user_approval = in_array("approval", $user_role);
            // $user_order = in_array("order", $user_role);
            // $with_rush_remarks = !empty($this->input("rush"));
            // if (!$user_approval) {
            //     return $validator->errors()->add("scope_approval", "this field is required.");
            // } elseif ($user_order) {
            //     return $validator->errors()->add("scope_order", "this field is required.");
            // }
        });
    }
}
