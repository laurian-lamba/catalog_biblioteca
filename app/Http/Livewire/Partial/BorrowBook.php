<?php

namespace App\Http\Livewire\Partial;

use App\Facades\Common;
use App\Facades\Util;
use App\Models\IssueBookReq;
use App\Models\SubBook;
use App\Traits\CustomCommonLive;
use Livewire\Component;

class BorrowBook extends Component
{
    public $user_id;
    public $book_id;
    public $available_books = [];
    public $book_obj;
    public $books_available;
    use CustomCommonLive;

    public function render()
    {
        return view('livewire.partial.borrow-book');
    }

    public function listAvailableBooks()
    {
        $this->available_books = SubBook::where("book_id", $this->book_id)->where("borrowed", 0)->get();
    }

    public function borrowThis($id)
    {
        $cnt = IssueBookReq::where("user_id", $this->user_id)->count();
        if ($cnt <= (Common::getLimitOfBookAssigned($this->user_id)-Common::getNoOfBooksBorrowedCurrently($this->user_id))) {
            IssueBookReq::updateOrCreate(["user_id" => $this->user_id, "sub_book_id" => $id]);
            $this->sendMessage(__("commonv2.book_borrowed_wait_for_approval"), "success");
        } else {
            $this->sendMessage(__("commonv2.you_hv_reached_ur_limit"), "error");
        }
    }

    public function cancelThis($id)
    {
        $item = IssueBookReq::where("user_id", $this->user_id)->where("sub_book_id", $id)->first();
        if ($item) {
            $item->delete();
        }
    }
}
