<?php

namespace WaseelMufti\CampaignRunner\Http\Controllers;

use WaseelMufti\CampaignRunner\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use WaseelMufti\CampaignRunner\Contracts\CustomerRepositoryInterface;

class CustomerController extends \App\Http\Controllers\Controller
{
    protected $customer;

    public function __construct(CustomerRepositoryInterface $customers)
    {
        $this->customers = $customers;
    }

    public function index(Request $request)
    {
        $data = [];
        if($request->filled('filters') && is_array($request->filters)){
            $data = $this->customers->search($request->filters);
        }else{
            $data = $this->customers->all();
        }
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'phone_number' => 'required|string|unique:customers',
            'status' => 'nullable|in:Paid,Grace period,Expired',
            'plan_expiry_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $customer = $this->customers->create($data);
        return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully.',
                'data' => $customer]);
    }

    public function show($id)
    {
        $customer = $this->customers->find($id);
        if(!$customer){
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }
        return response()->json(["status" => "success", "data" => $customer]);
    }

    public function update(Request $request, $id)
    {
        $customer = $this->customers->find($id);

        if(!$customer){
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone_number' => 'required|string|unique:customers,phone_number,' .  $customer->id,
            'status' => 'nullable|in:Paid,Grace period,Expired',
            'plan_expiry_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();

        $updatedCustomer = $this->customers->update($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully.',
            'data' => $updatedCustomer]);
    }

    public function destroy($id)
    {
        $this->customers->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Customer deleted successfully']);
    }

    public function importData(Request $request){

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|extensions:sql,txt|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->file('file')->getRealPath();
        $sql = file_get_contents($path);

        // Block CREATE DATABASE and CREATE TABLE statements
        if (preg_match('/\bCREATE\s+(DATABASE|TABLE)\b/i', $sql)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The SQL file contains CREATE DATABASE or CREATE TABLE statements, which are not allowed.'
            ], 400);
        }

        try {
            DB::unprepared($sql);
            return response()->json([
                'status' => "success",
                'message' => 'SQL file imported successfully!'
            ], 200);
        } catch (\Exception $e) {
            \Log::debug('Error importing SQL: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }
    }

}
