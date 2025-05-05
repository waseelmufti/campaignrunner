<?php

namespace WaseelMufti\CampaignRunner\Http\Controllers;

use WaseelMufti\CampaignRunner\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;
use WaseelMufti\CampaignRunner\Contracts\CustomerRepositoryInterface;

class CampaignCustomerController extends \App\Http\Controllers\Controller
{
    protected $campaigns;
    protected $customers;

    public function __construct(CampaignRepositoryInterface $campaigns, CustomerRepositoryInterface $customers)
    {
        $this->campaigns = $campaigns;
        $this->customers = $customers;
    }

    public function index(Request $request, $campaign)
    {
        $campaign = $this->campaigns->find($campaign);
        if (!$campaign) {
            return response()->json(['status' => 'error', 'message' => 'Campaign not found'], 404);
        }

        $customers = $this->campaigns->getCustomers($campaign);
        return response()->json(['status' => 'success', 'data' => ["campagin" => $campaign, "customers" => $customers]]);
    }

    public function store(Request $request, $campaign)
    {
        $campaign = $this->campaigns->find($campaign);
        if (!$campaign) {
            return response()->json(['status' => 'error', 'message' => 'Campaign not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'filters' => 'required|array',
            'filters.status' => 'required|in:Paid,Grace period,Expired',
            'filters.expires_in_days' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => "error",
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $response = $this->campaigns->attachCustomers($campaign, $data["filters"]);

        return response()->json([
            'status' => 'success',
            'message' => 'Customers attachted to Campaign successfully.',
        ]);
    }

    public function destroy($campaign, $customer)
    {
        $campaign = $this->campaigns->find($campaign);
        if (!$campaign) {
            return response()->json(['status' => 'error', 'message' => 'Campaign not found'], 404);
        }

        $this->campaigns->deleteCustomers($campaign, [$customer]);
        return response()->json(['status' => 'success', 'message' => 'Customer detached successfully']);
    }

}
