<?php

namespace WaseelMufti\CampaignRunner\Http\Controllers;

use WaseelMufti\CampaignRunner\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;

class CampaignController extends \App\Http\Controllers\Controller
{
    protected $campaigns;

    public function __construct(CampaignRepositoryInterface $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    public function index()
    {
        return response()->json(['status' => 'success', 'data' => $this->campaigns->all()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'subject' => 'required|string',
            'body' => 'nullable|string',
            'status' => 'nullable|in:Active,Inactive',
            'expiry_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $campaign = $this->campaigns->create($data);
        return response()->json([
                'status' => 'success',
                'message' => 'Campaign created successfully.',
                'data' => $campaign]);
    }

    public function show($id)
    {
        $campaign = $this->campaigns->find($id);
        if(!$campaign){
            return response()->json([
                'status' => 'error',
                'message' => 'Campaign not found'
            ], 404);
        }
        return response()->json(["status" => "success", "data" => $campaign]);
    }

    public function update(Request $request, $id)
    {
        $campaign = $this->campaigns->find($id);

        if(!$campaign){
            return response()->json([
                'status' => 'error',
                'message' => 'Campaign not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'subject' => 'required|string',
            'body' => 'nullable|string',
            'status' => 'nullable|in:Active,Inactive',
            'expiry_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();

        $updatedCustomer = $this->campaigns->update($id, $data);
        return response()->json([
            'status' => 'success',
            'message' => 'Campaign updated successfully.',
            'data' => $updatedCustomer]);
    }

    public function destroy($id)
    {
        $this->campaigns->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Campaign deleted successfully']);
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
