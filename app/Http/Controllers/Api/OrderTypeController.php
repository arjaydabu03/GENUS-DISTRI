<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OrderType;
use App\Response\Status;
use App\Functions\GlobalFunction;

use App\Http\Requests\OrderType\DisplayRequest;
use App\Http\Requests\OrderType\StoreRequest;
use App\Http\Requests\OrderType\CodeRequest;

class OrderTypeController extends Controller
{
    public function index(DisplayRequest $request)
    {
        $status = $request->status;
        $search = $request->search;
        $paginate = isset($request->paginate) ? $request->paginate : 1;

        $order_type = OrderType::when($status === "inactive", function ($query) {
            $query->onlyTrashed();
        })->when($search, function ($query) use ($search) {
            $query
                ->where("code", "like", "%" . $search . "%")
                ->orWhere("description", "like", "%" . $search . "%");
        });

        $order_type = $paginate
            ? $order_type->orderByDesc("updated_at")->paginate($request->rows)
            : $order_type->orderByDesc("updated_at")->get();

        $is_empty = $order_type->isEmpty();

        if ($is_empty) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        return GlobalFunction::response_function(Status::ORDERTYPE_DISPLAY, $order_type);
    }

    public function store(StoreRequest $request)
    {
        $order_type = OrderType::create([
            "code" => $request["code"],
            "description" => $request["description"],
        ]);
        return GlobalFunction::save(Status::ORDERTYPE_SAVE, $order_type);
    }

    public function update(StoreRequest $request, $id)
    {
        $order_type = OrderType::find($id);

        $not_found = OrderType::where("id", $id)->get();

        if ($not_found->isEmpty()) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        $order_type->update([
            "code" => $request["code"],
            "description" => $request["description"],
        ]);
        return GlobalFunction::response_function(Status::ORDERTYPE_UPDATE, $order_type);
    }
    public function destroy($id)
    {
        $order_type = OrderType::where("id", $id)
            ->withTrashed()
            ->get();

        if ($order_type->isEmpty()) {
            return GlobalFunction::not_found(Status::NOT_FOUND);
        }

        $order_type = OrderType::withTrashed()->find($id);
        $is_active = OrderType::withTrashed()
            ->where("id", $id)
            ->first();
        if (!$is_active) {
            return $is_active;
        } elseif (!$is_active->deleted_at) {
            $order_type->delete();
            $message = Status::ARCHIVE_STATUS;
        } else {
            $order_type->restore();
            $message = Status::RESTORE_STATUS;
        }
        return GlobalFunction::response_function($message, $order_type);
    }
    public function order_type_validate(CodeRequest $request)
    {
        return GlobalFunction::response_function(Status::SINGLE_VALIDATION);
    }
}
