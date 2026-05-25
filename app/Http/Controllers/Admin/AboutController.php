<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Support\Facades\Storage;

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
            if ($about->avatar) {
                Storage::disk('public')->delete($about->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('about', 'public');
        }

        if ($request->hasFile('cv_file')) {
            if ($about->cv_file) {
                Storage::disk('public')->delete($about->cv_file);
            }
            $data['cv_file'] = $request->file('cv_file')->store('about/cv', 'public');
        }

        $about->fill($data)->save();

        return redirect()->route('admin.about.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
