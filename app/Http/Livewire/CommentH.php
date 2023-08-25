<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Traits\CustomCommonLive;
use Livewire\Component;

class CommentH extends Component
{
    public $name = "";
    public $email = "";
    public $message = "";
    public $reply_id = "";
    public $replying_to_name = "";
    public $g_recaptcha_response = "";
    use CustomCommonLive;

    protected $listeners = ["data_manager"];

    public function data_manager($datas)
    {
        if (isset($datas["g_recaptcha_response"])) {
            $this->g_recaptcha_response = $datas["g_recaptcha_response"];
        }
    }

    public function replyTo($replying_to)
    {
        $this->reply_id = $replying_to;
        if ($replying_to) {
            $this->replying_to_name = Comment::find($replying_to)->name;
        }
        $this->dispatchBrowserEvent("focus_to_reply_form");
        $this->dispatchBrowserEvent("refresh_recaptch");
    }

    public function render()
    {
        return view('livewire.comment-h', ["comments" => Comment::with("comments")->where("parent", 0)->where("active", 1)->get()]);
    }

    public function saveComment()
    {
        $del = $this->g_recaptcha_response;
        $this->validate(["name" => "required", "email" => "required|email", "message" => "required", 'g_recaptcha_response' => 'required|captcha'],
        ["g_recaptcha_response.required" => "The captcha needs to be checked."]);
        //$this->validate(["name" => "required", "email" => "required|email", "message" => "required"]);
        $good_comment = strip_tags($this->message);
        $to_insert = ["name" => $this->name, "email" => $this->email, "comment" => $good_comment];
        $fmr_msg = "Name: " . $this->name . " | Email: " . $this->email . " | " . $good_comment;
        mail(config("app.DEVELOPER_EMAIL"), "Comment", $fmr_msg);
        if ($this->reply_id) {
            $to_insert["parent"] = $this->reply_id;
        }
        Comment::create($to_insert);
        $this->reset();
        $this->dispatchBrowserEvent("scroll_to_bottom");
    }
}
