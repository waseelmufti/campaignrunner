<?php

namespace WaseelMufti\CampaignRunner\Repositories;

use WaseelMufti\CampaignRunner\Contracts\CustomerRepositoryInterface;
use WaseelMufti\CampaignRunner\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $pageSize;

    public function __construct(){
        $this->pageSize = config('campaignrunner.page_size');
    }

    public function all()
    {
        return Customer::paginate($this->pageSize);
    }

    public function find($id)
    {
        return Customer::find($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update($id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function delete($id)
    {
        return Customer::destroy($id);
    }

    public function search(array $filters, array $select = [], $isPaginated = true)
    {
        $query = Customer::query();

        if(!empty($select)){
            $query->select($select);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['expires_in_days']) && is_numeric($filters['expires_in_days'])) {
            $query->whereDate('plan_expiry_date', '<=', now()->addDays($filters['expires_in_days']));
        }

        if($isPaginated){
            return $query->paginate($this->pageSize);
        }
        return $query->get();
    }
}
