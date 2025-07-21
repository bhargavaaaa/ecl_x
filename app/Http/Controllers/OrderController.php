<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Role;
use App\Traits\OrderAuthorizable;
use App\DataTables\OrderDataTable;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use OrderAuthorizable;

    /**
     * Display a listing of the resource.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = getCountries();
        $roles = Role::pluck('name', 'id');
        return response()->view('order.create', compact('roles', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $input = $request->safe()->except('password', 'role_id');
        $input["password"] = bcrypt($request->password);
        $order = Order::create($input);
        $order->syncRoles([$request->role_id]);

        return redirect()->route('order.index')->with('success', 'Order has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $countries = getCountries();
        $order = Order::whereNot('id', Order::first()->id)->findOrFail($id);
        $roles = Role::pluck('name', 'id');

        return response()->view('order.edit', compact('order', 'roles', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        $order = Order::whereNot('id', Order::first()->id)->findOrFail($id);
        $input = $request->safe()->except('password', 'role_id');
        if(!empty($request->password)) {
            $input["password"] = bcrypt($request->password);
        }
        $order->update($input);
        $order->syncRoles([$request->role_id]);

        return redirect()->route('order.index')->with('success', 'Order has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        if (!isOrderOpenToDelete($order)) {
            return response()->json(["status" => false, "message" => "Order can not due to transaction already done in order, you can delete the order by manually delete the transaction against the order."]);
        }

        $order->delete();

        return response()->json(["status" => true, "message" => "Order has been deleted."]);
    }

    public function checkWorkOrderNoUnique(Request $request, string $id = null)
    {
        $work_order_number = $request->input('work_order_number');
        if(empty($id)) {
            $isUnique = Order::where('work_order_number', $work_order_number)->count() === 0;
        } else {
            $isUnique = Order::where('work_order_number', $work_order_number)->whereNot('id', $id)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }
}
