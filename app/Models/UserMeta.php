<?php

namespace App\Models;

use App\Traits\CustomCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserMeta extends Model
{
    use CustomCache;
    use HasFactory;

    protected $guarded = ["id"];

    public function get_address()
    {
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "address")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "address")->where("user_id", $this->user_id)->first();
        }
        return $tmp ? $tmp->meta_value : "";
    }

    public function get_phone()
    {
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "phone")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "phone")->where("user_id", $this->user_id)->first();
        }
        return $tmp ? $tmp->meta_value : "";
    }

    public function get_education()
    {
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "education")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "education")->where("user_id", $this->user_id)->first();
        }
        return $tmp ? $tmp->meta_value : "";
    }

    public function get_about_me()
    {
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "about_me")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "about_me")->where("user_id", $this->user_id)->first();
        }
        return $tmp ? $tmp->meta_value : "";
    }

    public function get_assign()
    {
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "assigned_to")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "assigned_to")->where("user_id", $this->user_id)->first();
        }
        return $tmp ? $tmp->meta_value : "{}";
    }

    public function get_user_photo()
    {
        $email = "";
        if (!isset($this->user_id)) {
            $tmp = UserMeta::where("meta_key", "photo")->where("user_id", Auth::id())->first();
        } else {
            $tmp = UserMeta::where("meta_key", "photo")->where("user_id", $this->user_id)->first();
            $email = User::find($this->user_id)->email;
        }
        if ($tmp) {
            if (!empty($tmp->meta_value)) {
                if (\File::exists(public_path("uploads/" . $tmp->meta_value))) { // Just to be on safer side
                    return $tmp->meta_value;
                } else {
                    if (\File::exists(public_path("uploads/" . $this->user_id . ".jpg"))) {
                        return $this->user_id . ".jpg";
                    }
                    $data = Str::of($email)->explode("@");
                    if (count($data) == 2 && \File::exists(public_path("uploads/" . $data[0] . ".jpg"))) {
                        return $data[0] . ".jpg";
                    }
                    $tmp = UserMeta::where("user_id", $this->user_id)->where("meta_key", "phone")->first();
                    if ($tmp) {
                        if (\File::exists(public_path("uploads/" . $tmp->meta_value.".jpg"))) { // Just to be on safer side
                            return $tmp->meta_value.".jpg";
                        }
                    }
                    return config("app.DEFAULT_USR_IMG");;
                }
            }
        } else {
            if (\File::exists(public_path("uploads/" . $this->user_id . ".jpg"))) {
                return $this->user_id . ".jpg";
            }
            $data = Str::of($email)->explode("@");
            if (count($data) == 2 && \File::exists(public_path("uploads/" . $data[0] . ".jpg"))) {
                return $data[0] . ".jpg";
            }
            $tmp = UserMeta::where("user_id", $this->user_id)->where("meta_key", "phone")->first();
            if ($tmp) {
                if (\File::exists(public_path("uploads/" . $tmp->meta_value.".jpg"))) { // Just to be on safer side
                    return $tmp->meta_value.".jpg";
                }
            }
            return config("app.DEFAULT_USR_IMG");
        }
    }
}
