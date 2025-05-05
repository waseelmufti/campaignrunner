<?php
namespace WaseelMufti\CampaignRunner\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone_number', 'status', 'plan_expiry_date'];

    public function campaigns(){
        return $this->belongsToMany(Campaign::class, "campaigns_customers", 'customer_id', 'campaign_id')
                    ->withPivot("delivery_status");
    }
}
