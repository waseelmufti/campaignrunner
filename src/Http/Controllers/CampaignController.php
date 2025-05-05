<?php

namespace WaseelMufti\CampaignRunner\Http\Controllers;

use WaseelMufti\CampaignRunner\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use WaseelMufti\CampaignRunner\Jobs\SendCampaignEmailJob;
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

    public function runCampaign($id)
    {
        $campaign = $this->campaigns->find($id);

        if(!$campaign){
            return response()->json([
                'status' => 'error',
                'message' => 'Campaign not found'
            ], 404);
        }

        SendCampaignEmailJob::dispatch($campaign);

        return response()->json(['status' => 'success', 'message' => 'Emails queued for sending.'], 200);
    }

}
