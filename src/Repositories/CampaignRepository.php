<?php

namespace WaseelMufti\CampaignRunner\Repositories;

use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;
use WaseelMufti\CampaignRunner\Models\Campaign;

class CampaignRepository implements CampaignRepositoryInterface
{
    private $pageSize;

    public function __construct(){
        $this->pageSise = config('campaignrunner.page_size');
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

}
