<?php

namespace App\Services;

use App\Actions\CreateModel;
use App\Jobs\SendCampaign;
use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\Lead;
use Exception;
use Illuminate\Support\Facades\Auth;

class MarketingService
{
    protected $createModelAction;

    public function __construct(CreateModel $createModelAction)
    {
        $this->createModelAction = $createModelAction;
    }

    public function createLead($data)
    {
        $this->createModelAction->handle(new Lead, $data, Auth::user(), $data['owner_id']);
    }

    public function createTemplate($data)
    {
        $sendId = uniqid();
        $template = $data['template'];

        $trackingPixel = '<img src="' . route('email.open', ['send_id' => $sendId]) . '" width="1" height="1" style="display:none;" />';

        if (stripos($template, '</body>') !== false) {
            $template = str_ireplace('</body>', $trackingPixel . '</body>', $template);
        } else {
            $template .= $trackingPixel;
        }

        $data['template'] = $template;
        $this->createModelAction->handle(new EmailTemplate, $data, Auth::user());

        $template = preg_replace_callback(
            '/<a\s+[^>]*href=["\']([^"\']+)["\']/i',
            function ($matches) use ($sendId) {
                $originalUrl = $matches[1];

                // Generate tracked URL
                $trackedUrl = route('email.click', [
                    'send_id' => $sendId,
                    'url' => urlencode($originalUrl),
                ]);

                // Replace original href with tracked URL
                return str_replace($originalUrl, $trackedUrl, $matches[0]);
            },
            $template
        );

        // --- 3. Save modified template back to campaign ---
        // $campaign->update([
        //     'template' => $template
        // ]);
    }

    public function createCampaign($request)
    {
        try {
            $this->createModelAction->handle(new Campaign, $request->all(), Auth::user());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sendCampaign($data)
    {
        try {
            $campaign = Campaign::find($data['campaign_id']);

            if (! $campaign) {
                throw new Exception('Campaign not found', 404);
            }
            dispatch(new SendCampaign($campaign));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function scheduleCampaign($request)
    {
        $campaign = Campaign::find($request->campaign_id);

        if (!$campaign)
            throw new Exception('Campaign not found', 404);

        $campaign->update(['status' => 'scheduled']);
    }
}
/* public function track($id)
    {
        $log = EmailLog::find($id);

        if ($log) {
            $log->clicked_at = now();
            $log->click_count += 1;
            $log->save();
        }

        // Redirect to real page
        return redirect()->away($log->target_url ?? 'https://yourdomain.com');
    }
        */