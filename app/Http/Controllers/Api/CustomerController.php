<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer;
use App\Response\Status;
use App\Functions\GlobalFunction;

use App\Http\Requests\Customer\DisplayRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Requests\Customer\CodeRequest;
use App\Http\Requests\Customer\ImportRequest;

class CustomerController extends Controller
{
    public function index(DisplayRequest $request)
    {
        $status = $request->status;
        $search = $request->search;
        $paginate = isset($request->paginate) ? $request->paginate : 1;

        $customer = Customer::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })->when($search, function ($query) use ($search) {
            $query
                ->where("code", "like", "%" . $search . "%")
                ->orWhere("name", "like", "%" . $search . "%");
        });

        $customer = $paginate
            ? $customer->orderByDesc("updated_at")->paginate($request->rows)
            : $customer->orderByDesc("updated_at")->get();

        $is_empty = $customer->isEmpty();

        if ($is_empty) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        return GlobalFunction::response_function(Status::CUSTOMER_DISPLAY, $customer);
    }

    public function store(StoreRequest $request)
    {
        $customer = Customer::create([
            "code" => $request["code"],
            "name" => $request["name"],
        ]);
        return GlobalFunction::save(Status::CUSTOMER_SAVE, $customer);
    }
    public function import_cutomer(ImportRequest $request)
    {
        $import = $request->all();

        $import = Customer::upsert($import, ["id"], ["code"], ["name"]);

        return GlobalFunction::save(Status::CUSTOMER_IMPORT, $request->toArray());
    }
    public function update(StoreRequest $request, $id)
    {
        $customer = Customer::find($id);

        $not_found = Customer::where("id", $id)->get();

        if ($not_found->isEmpty()) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        $customer->update([
            "code" => $request["code"],
            "name" => $request["name"],
        ]);
        return GlobalFunction::response_function(Status::CUSTOMER_UPDATE, $customer);
    }
    public function destroy($id)
    {
        $customer = Customer::where("id", $id)
            ->withTrashed()
            ->get();

        if ($customer->isEmpty()) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        $customer = Customer::withTrashed()->find($id);
        $is_active = Customer::withTrashed()
            ->where("id", $id)
            ->first();
        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $customer->delete();
            $message = Status::ARCHIVE_STATUS;
        } else {
            $customer->restore();
            $message = Status::RESTORE_STATUS;
        }
        return GlobalFunction::response_function($message, $customer);
    }
    public function validate_cutomer_code(CodeRequest $request)
    {
        return GlobalFunction::response_function(Status::SINGLE_VALIDATION);
    }
}
