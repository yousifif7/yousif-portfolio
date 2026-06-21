<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\HireFormRequest;
use App\Mail\HireRequestMail;
use App\Models\DevelopmentOffering;
use App\Models\HireRequest;
use App\Models\Review;
use App\Support\HireFormOptions;
use App\Support\PublicUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HireController extends Controller
{
    public function show()
    {
        $offerings = DevelopmentOffering::active()->ordered()->get();
        $countryCodes = HireFormOptions::countryCodes();
        $engagementModels = HireFormOptions::engagementModels();
        $projectPhases = HireFormOptions::projectPhases();
        $projectContext = request()->query('project');
        $projectContext = is_string($projectContext) ? trim(strip_tags($projectContext)) : null;
        if ($projectContext === '') {
            $projectContext = null;
        }
        $faqs = config('hire.faq', []);
        $featuredReview = Review::approved()->featured()->first();

        return view('site.hire', compact(
            'offerings',
            'countryCodes',
            'engagementModels',
            'projectPhases',
            'projectContext',
            'faqs',
            'featuredReview',
        ));
    }

    public function store(HireFormRequest $request)
    {
        $data = $request->validated();
        unset($data['website'], $data['form_token'], $data['cf-turnstile-response']);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = PublicUpload::store($request->file('attachment'), 'hire');
        }

        $hireRequest = HireRequest::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'whatsapp_country_code' => $data['whatsapp_country_code'],
            'whatsapp_number' => $data['whatsapp_number'],
            'offerings' => $data['offerings'],
            'engagement_models' => $data['engagement_models'] ?? [],
            'project_phases' => $data['project_phases'] ?? [],
            'message' => $data['message'] ?? null,
            'attachment_path' => $attachmentPath,
            'terms_agreed' => true,
            'ip_address' => $request->ip(),
        ]);

        try {
            $adminEmail = config('services.admin_email');
            if ($adminEmail) {
                $offeringTitles = DevelopmentOffering::whereIn('id', $hireRequest->offerings ?? [])
                    ->pluck('title')
                    ->all();

                Mail::to($adminEmail)->send(new HireRequestMail($hireRequest, $offeringTitles));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send hire request notification: '.$e->getMessage());
        }

        return redirect()
            ->route('hire')
            ->with('success', 'Thank you! Your hire request has been submitted. I will review it and get back to you soon.');
    }
}
