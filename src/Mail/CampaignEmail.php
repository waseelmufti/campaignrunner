<?php
namespace WaseelMufti\CampaignRunner\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $recipient;

    public function __construct($campaign, $recipient)
    {
        $this->campaign = $campaign;
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
                    ->view('campaignrunner::emails.campaign')
                    ->with([
                        'title' => $this->campaign->title,
                        'content' => $this->campaign->content,
                        'recipient' => $this->recipient,
                    ]);
    }
}
