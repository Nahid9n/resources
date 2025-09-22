<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffLoginController extends Controller
{
    //login page
    public function login_form()
    {
        // dd('ddd');
        return view('backEnd.staff.auth.login');
    }
    public function login(Request $request)
    {
        // dd($request->all());
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {
        // dd(4444);
        Auth::guard('staff')->logout();
        return redirect('/login');
    }

    public function profile()
    {
        $data = Staff::where('id', auth()->user()->id)->first();
        return view('backEnd.staff.auth.profile', compact('data'));

    }
    public function profileEdit()
    {
        $data = Staff::where('id', auth()->user()->id)->first();
        return view('backEnd.staff.auth.profile-edit', compact('data'));

    }
    public function update(Request $request)
    {
        $staff = auth()->user();
        $input = $request->all();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $fileName = uniqid();
            $directory = 'uploads/profile/';

            // Delete old files
            if(!empty($staff->original_avatar) && file_exists(public_path($staff->original_avatar))){
                unlink(public_path($staff->original_avatar));
            }
            if(!empty($staff->avatar) && file_exists(public_path($staff->avatar))){
                unlink(public_path($staff->avatar));
            }

            // 1️⃣ Original image (original format)
            $originalExtension = strtolower($avatar->getClientOriginalExtension());
            $originalPath = $directory . $fileName . '.' . $originalExtension;
            $avatar->move(public_path($directory), $fileName . '.' . $originalExtension);
            $input['original_avatar'] = $originalPath;

            // 2️⃣ Resized 100x100 WebP avatar
            $resizedPath = $directory . 'avatar_' . $fileName . '.webp';
            $this->convertToWebp(public_path($originalPath), public_path($resizedPath), 100, 100);
            $input['avatar'] = $resizedPath;
        }

        $staff->update($input);

        return redirect()->back()->with('success', 'Profile Updated Successfully!');
    }

    /**
     * Convert any image to WebP and resize
     */
    private function convertToWebp($sourcePath, $destinationPath, $width, $height)
    {
        $mimeType = mime_content_type($sourcePath);

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                throw new \Exception("Unsupported image type: $mimeType");
        }

        $resized = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG & WebP
        if(in_array($mimeType, ['image/png', 'image/webp'])) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        list($origWidth, $origHeight) = getimagesize($sourcePath);
        imagecopyresampled($resized, $image, 0,0,0,0, $width, $height, $origWidth, $origHeight);

        imagewebp($resized, $destinationPath, 90);
        imagedestroy($image);
        imagedestroy($resized);
    }


    // password update
    public function password_update(Request $request)
    {
        $data = Staff::find(auth()->user()->id);
        if (Hash::check($request->password, $data->password)) {
            $n = [
                'password' => bcrypt($request->new_password),
            ];
            $data->update($n);
            auth()->logout();
            return redirect()->back()->with('message', 'Password Updated Successfully!');
        } else {
            return redirect()->back()->with('errors', 'Password not matched!');
        }
    }

}
