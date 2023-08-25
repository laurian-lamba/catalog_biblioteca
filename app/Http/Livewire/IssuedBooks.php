<?php

namespace App\Http\Livewire;

use App\Custom\BookCallBacks;
use App\Facades\Util;
use App\Helper\Common;
use App\Models\Borrowed;
use App\Traits\CustomCommonLive;
use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class IssuedBooks extends Component
{
    public $search_keyword;
    public $return_date;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $fine;
    public $remark;
    public $sql_query;
    public $dd_filter=false;
    use CustomCommonLive;

    protected $listeners = ["data_manager", "receiveBook", "markLostBook","markDamageBook"];

    public function data_manager($datas)
    {
        if (isset($datas["return_date"])) {
            $this->return_date = $datas["return_date"];
        }
        if (isset($datas["fine"])) {
            $this->fine = $datas["fine"] ?? null;
        }
        if (isset($datas["remark"])) {
            $this->remark = $datas["remark"] ?? null;
        }
    }


    public function load_data()
    {
        $search_keyword = $this->search_keyword;
        $sub_books = \App\Models\SubBook::where("sub_book_id", "like", '%' . $search_keyword . '%')->where("active", 1)->pluck("id")->toArray();
        $users = \App\Models\User::where("name", "like", '%' . $search_keyword . '%')->where("active", 1)->pluck("id")->toArray();
        $adv_filter = false;
        if (Str::contains($search_keyword, ",")) {
            $sub_books = \App\Models\SubBook::whereIn("sub_book_id", explode(",", $search_keyword))->where("active", 1)->pluck("id")->toArray();
            $users = \App\Models\Borrowed::whereIn("user_id", explode(",", $search_keyword))->pluck("user_id")->toArray();
            $adv_filter = true;
        }


        $books_borrowed = Borrowed::with(["book", "sub_book", "payment","user","issued_by_person"]);
        if ($this->search_keyword) {
            $books_borrowed = $books_borrowed->whereIn("sub_book_id", $sub_books)
                ->orWhere("remark", "like", "%" . $search_keyword . "%");
            $books_borrowed = $books_borrowed->orwhereIn("user_id", $users);
        }
        if (!$adv_filter) {
            $books_borrowed = $books_borrowed->where("working_year", \App\Facades\Common::getWorkingYear());
        }
        if ($search_keyword) {
            $this->resetPage();
        }
        if(!$this->dd_filter) {
            $books_borrowed = $books_borrowed->orderBy("id", "desc");
        }else{
            $books_borrowed = $books_borrowed->whereNull("date_returned")->orderBy("id", "desc");
        }
        return $books_borrowed->has("book")->has("sub_book")->paginate(10);
    }

    public function refresh_elements()
    {
        if ($this->return_date) {
            $this->dispatchBrowserEvent("refresh_elements", ["return_date" => $this->return_date]);
        } else {
            $this->dispatchBrowserEvent("refresh_elements");
        }
    }

    public function mount()
    {
        $this->refresh_elements();
    }

    public function render()
    {
        $this->refresh_elements();
        $this->dispatchBrowserEvent("tooltip_refresh");
        return view('livewire.issued-books', ["items" => $this->load_data()]);
    }

    public function receiveBook($datas)
    {

        $datas = Util::getCleanedLiveArray($datas);
        $id = isset($datas["id"]) ? $datas["id"] : 0;
        if ($id) {
            $tmp = Borrowed::find($id);
            list($tmp) = (new BookCallBacks())->beforeBookIsReceived($tmp);
            if ($tmp) {
                if (Carbon::parse($this->return_date) < Carbon::parse($tmp->date_borrowed)) {
                    $this->sendMessage(__("common.return_day_error"), "error");
                    return;
                }
                $tmp->date_returned = Carbon::parse($this->return_date);
                if (Carbon::parse($this->return_date) > Carbon::parse($tmp->date_to_return)) {
                    $tmp->delayed_day = Carbon::parse($this->return_date)->diffInDays(Carbon::parse($tmp->date_to_return));
                }
                if ($this->remark) {
                    $tmp->remark = $this->remark;
                }
                if ($this->fine) {
                    $tmp->fine = $this->fine;
                }
                $s_obj = \App\Models\SubBook::find($tmp->sub_book_id);
                if ($s_obj) {
                    $s_obj->borrowed = 0;
                    $s_obj->save();
                }
                $tmp->save();
                (new BookCallBacks())->afterBookIsReceived();
            }
            $this->sendMessage(__("common.book_return_success"), "success");
        } else {
            $this->sendMessage(__("common.id_missing"), "error");
        }
    }
    public function markDamageBook($datas){
        $datas = Util::getCleanedLiveArray($datas);
        $id = isset($datas["id"]) ? $datas["id"] : 0;
        $uid = isset($datas["uid"]) ? $datas["uid"] : null;
        if ($id) {
            $temp = \App\Models\SubBook::find($id);
            if ($temp) {
                if ($uid) {
                    $temp->damaged_by = $uid;
                    $temp->condition = 2;
                }
                $temp->save();
                $this->sendMessage(__("commonv2.done_marked_as_damaged"), "success");
            }
        } else {
            $this->sendMessage(__("common.id_missing"), "error");
        }
    }
    public function markLostBook($datas)
    {
        $datas = Util::getCleanedLiveArray($datas);
        $id = isset($datas["id"]) ? $datas["id"] : 0;
        $uid = isset($datas["uid"]) ? $datas["uid"] : null;
        if ($id) {
            $temp = \App\Models\SubBook::find($id);
            if ($temp) {
                $temp->active = 0;
                if ($uid) {
                    $temp->lost_by = $uid;
                }
                $temp->save();
                $this->sendMessage(__("common.done_mark_has_lost"), "success");
            }
        } else {
            $this->sendMessage(__("common.id_missing"), "error");
        }
    }
}
