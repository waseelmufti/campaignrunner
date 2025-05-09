<?php

namespace WaseelMufti\CampaignRunner\Repositories;

use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;
use WaseelMufti\CampaignRunner\Models\Campaign;

class CampaignRepository implements CampaignRepositoryInterface
{
    private $pageSize;
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository){
        $this->pageSize = config('campaignrunner.page_size');
        $this->customerRepository = $customerRepository;
    }

    public function all()
    {
        return Campaign::paginate($this->pageSize);
    }

    public function find($id)
    {
        return Campaign::find($id);
    }

    public function create(array $data)
    {
        return Campaign::create($data);
    }

    public function update($id, array $data)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update($data);
        return $campaign;
    }

    public function delete($id)
    {
        return Campaign::destroy($id);
    }

    public function search(array $filters)
    {
        $query = Campaign::query();

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['subject'])) {
            $query->where('subject', 'like', '%' . $filters['subject'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    public function getCustomers(Campaign $campaign, $isPaginated = false, array $pivotFilters = [])
    {
        $campaignQuery = $campaign->customers();
        if(!empty($pivotFilters)){
            $campaignQuery->wherePivot($pivotFilters);
        }

        if($isPaginated){
            return $campaignQuery->paginate($this->pageSize);
        }

        return $campaignQuery->get();
    }

    public function attachCustomers(Campaign $campaign, array $customerFilters)
    {
        $customerIds = $this->customerRepository->search($customerFilters, ['id'], false);
        $customerIds = $customerIds->pluck('id')->toArray();
        return $campaign->customers()->sync($customerIds);
    }

    public function deleteCustomers(Campaign $campaign, array $customerIds = []){
        return $campaign->customers()->detach($customerIds);
    }

    public function updateDeliveryStatus(Campaign $campaign, $customerId, $status){
        return $campaign->customers()->updateExistingPivot($customerId, [
            'delivery_status' => $status,
        ]);
    }

}
