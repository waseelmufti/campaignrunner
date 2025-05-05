<?php
namespace WaseelMufti\CampaignRunner\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['title', 'subject', 'body', 'status', 'expiry_date'];

    public function customers(){
        return $this->belongsToMany(Customer::class, "campaigns_customers", 'campaign_id', 'customer_id')
                    ->withPivot("delivery_status");
    }
}
