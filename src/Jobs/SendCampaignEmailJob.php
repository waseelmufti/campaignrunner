<?php

namespace WaseelMufti\CampaignRunner\Jobs;

use Illuminate\Support\Facades\App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use WaseelMufti\CampaignRunner\Mail\CampaignEmail;
use WaseelMufti\CampaignRunner\Contracts\CampaignRepositoryInterface;
use WaseelMufti\CampaignRunner\Contracts\CustomerRepositoryInterface;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $pageSize;

    public function __construct($campaign)
    {
        $this->campaign = $campaign;
        $this->pageSize = config('campaignrunner.page_size');
    }

    public function handle(): void
    {
        $campaignRepo = App::make(CampaignRepositoryInterface::class);
        $customerRepo = App::make(CustomerRepositoryInterface::class);
        $currentCustomerId = null;

        try {

            foreach($campaignRepo->getCustomers($this->campaign, false, ['status' => 'Queued'])->chunk($this->pageSize) as $customers){
                foreach($customers as $customer){
                    $currentCustomerId = $customer->id;
                    Mail::to($customer->email)->send(new CampaignEmail($this->campaign, $customer));
                    $campaignRepo->updateDeliveryStatus($this->campaign, $currentCustomerId, 'Sent');

                }
            }
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            $campaignRepo->updateDeliveryStatus($this->campaign, $currentCustomerId, 'Failed');
        }
    }
}
