<?php

namespace App\Http\Controllers;

use App\Facades\Util;
use App\Http\Livewire\SubBook;
use App\Models\Borrowed;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("back.report");
    }

    public function store(Request $request)
    {
        $todo = $request->to_do;
        $book_details = null;
        $title = "--";
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->startOfDay();
        $fast_tag = $request->fast_tag;
        switch ($fast_tag) {
            case "this_week":
                $start_date = Carbon::now()->startOfWeek();
                $end_date = Carbon::now();
                break;
            case "last_year":
                $start_date = Carbon::now()->startOfYear()->subYear();
                $end_date = Carbon::now()->endOfYear()->subYear();
                break;
            case "this_year":
                $start_date = Carbon::now()->startOfYear();
                $end_date = Carbon::now();
                break;
            case "last_month":
                $start_date = Carbon::now()->startOfMonth()->subMonth();
                $end_date = Carbon::now()->endOfMonth()->subMonth();
                break;
            case "this_month":
                $start_date = Carbon::now()->startOfMonth();
                $end_date = Carbon::now();
                break;
            case "last_30":
                $start_date = Carbon::now()->subDays(30);
                $end_date = Carbon::now();
                break;
            default:
                break;
        }
        if (!empty($todo)) {
            switch ($todo) {
                case "most_issued_books":
                    $book_details = Borrowed::with(["book"])
                        ->selectRaw("book_id,count(book_id) as count,min(created_at) as created_at")
                        ->groupBy("book_id")->limit(20);
                    $title = __("commonv2.rp_most_issued_books");
                    break;
                case "damage_books":
                    $book_details = \App\Models\SubBook::with(["book", "user_damaged"])->where("damaged_by", "<>", null);
                    $title = __("commonv2.rp_damaged_books");
                    break;
                case "losted_books":
                    $book_details = \App\Models\SubBook::with(["book", "user_lost"])->where("lost_by", "<>", null);
                    $title = __("commonv2.rp_losted_books");
                    break;
                case "late_returned":
                    $book_details = Borrowed::with(["book", "user", "issued_by_person", "sub_book"])->whereRaw("date_returned > date_to_return");
                    $title = __("commonv2.rp_late_return_books");
                    break;
                case "fines_collected":
                    $book_details = Borrowed::with(["book", "sub_book", "user", "issued_by_person"])->where("fine", "<>", null);
                    $title = __("commonv2.rp_fines_collected");
                    break;
                default:
                    $book_details = null;
                    break;
            }
            if ($book_details) {
                if ((!blank($request->start_date) && !blank($request->end_date) || !blank($request->fast_tag))) {
                    if($todo=="damage_books" || $todo=="losted_books"){
                        $book_details = $book_details->whereBetween("updated_at", [$start_date, $end_date]);
                    }else{
                    $book_details = $book_details->whereBetween("created_at", [$start_date, $end_date]);}
                }
                if (!blank($request->start_date)) {
                    if($todo=="damage_books" || $todo=="losted_books"){
                        $book_details = $book_details->whereBetween("updated_at", [$start_date, $end_date]);
                    }else{
                    $book_details = $book_details->whereBetween("created_at", [$start_date, Carbon::now()->endOfDay()]);}
                }
                //dd(Util::toSql($book_details));
                $book_details = $book_details->get();
            }
        } else {
            abort("404", __("commonv2.you_hv_not_selected_anything_to_begin_with"));
        }
        return view("templates.print_report", compact("book_details", "title", "todo"));
    }
}
