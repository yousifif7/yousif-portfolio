<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use App\Support\PublicUpload;

class AboutController extends Controller
{
    public function edit()
    {
        $about = About::firstOrNew([]);
        return view('admin.about.edit', compact('about'));
    }

    public function update(AboutRequest $request)
    {
        $data = $request->validated();
        $about = About::firstOrNew([]);

        if ($request->hasFile('avatar')) {
            PublicUpload::delete($about->avatar);
            $data['avatar'] = PublicUpload::store($request->file('avatar'), 'about');
        }

        if ($request->hasFile('cv_file')) {
            PublicUpload::delete($about->cv_file);
            $data['cv_file'] = PublicUpload::store($request->file('cv_file'), 'about/cv');
        }

        $about->fill($data)->save();

        return redirect()->route('admin.about.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
