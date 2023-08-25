<?php

namespace App\Http\Controllers;

use App\Facades\Common;
use App\Facades\Util;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubBookController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("back.book.sub_book.index");
    }

    public function getUserIds(Request $request)
    {
        $users = User::with("user_meta")->where("active", 1);
        if (is_numeric($request->term)) {
            $users = $users->where("id", $request->term);
        } else {
            $users = $users->where("name", "like", "%" . $request->term . "%");
        }
        $users = $users->get()->take(5);
        $tmp_users = [];
        foreach ($users as $user) {
            //#$user_img = Util::searchCollections($user->user_meta, "meta_key", "photo", "meta_value");
            $user_img = User::get_user_photo($user->id);
            $user["image"] = $user_img ? asset('uploads/' . $user_img) : asset('uploads/' . config('app.DEFAULT_USR_IMG'));
            $user["id"] = $user->id;
            $user["name"] = Str::title($user->name);
            $user["address"] = Util::getIfNotEmpty($user->get_user_address($user->id)) ?? "--";
            $user["email"] = $user->email;
            $user["year"] = $user->get_year($user->id);
            $user["course"] = $user->get_course($user->id);
            $user["year_name"] = Common::getCourseYearName($user["year"]);
            $user["course_name"] = Common::getCourseName($user["course"]);
            $user["borrowed_cnt"] = Common::getNoOfBooksBorrowedCurrently($user->id);
            $user["limit"] = Common::getLimitOfBookAssigned($user->id);
            array_push($tmp_users, $user);
        }
        return response()->json($tmp_users);
    }

    public function getUserBorrowedBookCount()
    {
        return response()->json(["borrowed" => Common::getNoOfBooksBorrowedCurrently(request()->user_id)]);
    }

    public function getBookIds(Request $request)
    {

        $search_keyword = $request->term;
        $books = \App\Models\SubBook::with(["book"])
            ->orWhere("sub_book_id", "like", "%" . $search_keyword . "%")->where("active", 1);
        $books = $books->where("active", 1)->get()->take(5);
        $tmp_books = [];
        foreach ($books as $book) {
            $book["title"] = Str::words($book->book->title, 3, "..");
            $book["bid"] = $book->sub_book_id;
            $book["condition"] = $book->condition ? "New" : "Old";
            $book["remark"] = Util::getIfNotEmpty($book->remark) ?? "--";
            $book["borrowed"] = $book->borrowed;
            $book["borrowed_class"] = $book->borrowed ? "text-red" : "";
            $book["book_m_id"] = $book->book_id;
            $book["price"] = $book->price;
            $book["category"] = $book->book->category;
            $book["image"] = $book->book->cover_img();
            array_push($tmp_books, $book);
        }
        return response()->json($tmp_books);
    }
}
