<?php

namespace WaseelMufti\CampaignRunner\Contracts;

use WaseelMufti\CampaignRunner\Models\Campaign;

interface CampaignRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function search(array $filters);

    public function getCustomers(Campaign $campaign);

    public function attachCustomers(Campaign $campaign, array $customers);

    public function deleteCustomers(Campaign $campaign, array $customerIds);

    public function updateDeliveryStatus(Campaign $campaign, $customerId, $status);
}
