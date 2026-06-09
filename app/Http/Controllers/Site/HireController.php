<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\HireFormRequest;
use App\Models\DevelopmentOffering;
use App\Models\HireRequest;
use App\Support\HireFormOptions;
use App\Support\PublicUpload;

class HireController extends Controller
{
    public function show()
    {
        $offerings = DevelopmentOffering::active()->ordered()->get();
        $countryCodes = HireFormOptions::countryCodes();
        $engagementModels = HireFormOptions::engagementModels();
        $projectPhases = HireFormOptions::projectPhases();

        return view('site.hire', compact(
            'offerings',
            'countryCodes',
            'engagementModels',
            'projectPhases'
        ));
    }

    public function store(HireFormRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = PublicUpload::store($request->file('attachment'), 'hire');
        }

        HireRequest::create([
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

        return redirect()
            ->route('hire')
            ->with('success', 'Thank you! Your hire request has been submitted. I will review it and get back to you soon.');
    }
}
