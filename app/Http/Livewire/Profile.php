<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{

    use WithFileUploads;
    public $current_user;
    public $name;
    public $email;
    public $address;
    public $phone;
    public $education;
    public $about_me;
    public $tab = '1';
    public $user_id = null;
    public $photo = "";
    public $proof = "";
    public $proof_link = "";
    public $photo_link;
    public $check = "";
    public $password_confirmation;
    public $password;
    public $current_password;

    public function mount()
    {
        $this->user_id = Auth::id();
        $user_obj = User::find($this->user_id);
        $this->current_user = $user_obj;
        $this->email = $user_obj->email;
        $this->name = $user_obj->name;
        $user_meta_obj = UserMeta::where("user_id", $user_obj->id)->first();
        if ($user_meta_obj) {
            $this->address = $user_meta_obj->get_address();
            $this->education = $user_meta_obj->get_education();
            $this->phone = $user_meta_obj->get_phone();
            $this->about_me = $user_meta_obj->get_about_me();
            $this->photo_link = $user_meta_obj->get_user_photo();
            $tmp = $user_obj->get_proof();
            $this->proof_link = !empty($tmp) ? $tmp : '';
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }

    public function saveProfile()
    {
        $this->resetErrorBag();
        session()->flash("form_profile", true);
        $this->validate([
            'photo' => 'nullable|image|max:1024', // 1MB Max
            'proof' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048', // 1MB Max
            "phone" => "required|min:10",
            "name" => "required",
            "check" => "required"
        ], ["phone.min" => __("common.phone_no_invalid"),
            "photo.image" => __("common.only_image"),
            "photo.max" => __("common.file_size_exceed"),
            "check.required" => __("common.terms_and_conditions_needs_to_be_checked"),
        ]);

        $user_obj = User::find($this->user_id);
        if ($user_obj) {
            $user_obj->name = $this->name;
            $user_obj->save();
            $path = config("app.DEFAULT_USR_IMG");;
            if ($this->photo) {
                $path = $this->photo->storePublicly('', 'custom');
                UserMeta::updateOrCreate(["meta_key" => "photo", "user_id" => $user_obj->id], ["meta_value" => $path]);
                $this->photo_link = $path;
            }
            if ($this->proof) {
                $path = $this->proof->storePublicly('', 'custom');
                UserMeta::updateOrCreate(["meta_key" => "proof", "user_id" => $user_obj->id], ["meta_value" => $path]);
                $this->proof_link = $path;
            }
            UserMeta::updateOrCreate(["meta_key" => "address", "user_id" => $user_obj->id], ["meta_value" => $this->address]);
            UserMeta::updateOrCreate(["meta_key" => "phone", "user_id" => $user_obj->id], ["meta_value" => $this->phone]);
            UserMeta::updateOrCreate(["meta_key" => "education", "user_id" => $user_obj->id], ["meta_value" => $this->education]);
            UserMeta::updateOrCreate(["meta_key" => "about_me", "user_id" => $user_obj->id], ["meta_value" => $this->about_me]);
            session()->flash("alert-success", __("common.profile_details_saved"));
            $this->check = "";
        }
    }

    public function savePassword()
    {
        session()->flash("form_change_password", true);
        $this->validate([
            "current_password" => "required",
            "password" => "required|min:8|confirmed"], [
            "password.required" => __("common.password_req"),
            "password.min" => __("common.password_8_char_long"),
            "password.confirmed" => __("common.password_and_confirm_password_dont_match")
        ]);
        $current_user_obj = User::find($this->user_id);
        if ($current_user_obj && Hash::check($this->current_password, $current_user_obj->password)) {
            $current_user_obj->password = Hash::make($this->password_confirmation);
            $current_user_obj->save();
            $this->current_password = "";
            $this->password = "";
            $this->password_confirmation = "";
            session()->flash("alert-success", __("common.password_change_successfully"));
        } else {
            session()->flash("alert-danger", __("common.password_and_confirm_password_dont_match"));
        }

    }
}
