<?php

use App\Facades\Common;
use App\Facades\Util;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseYearController;
use App\Http\Controllers\DeweyDecimalClassController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubBookController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\LanguageTranslationController;
use App\Models\Book;
use App\Http\Controllers\InstallerController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use voku\helper\UTF8;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Installation Link */
Route::get('installer/index', [InstallerController::class, "index"]);
Route::get('installer/requirements', [InstallerController::class, "serverRequirements"]);
Route::get('installer/configuration', [InstallerController::class, "environmentConfiguration"]);
Route::post('installer/configuration', [InstallerController::class, "postConfiguration"]);
Route::get('installer/complete', [InstallerController::class, "complete"]);
/* ------------------ */
if (!config('app.installed')) {
    return;
}

Auth::routes([
    "register" => Common::getSiteSettings("enable_register", false)
]);
Route::get("printing-id-cards/{ids}", function ($ids) {
    $user_list = User::with("user_meta")->whereIn("id", array_filter(explode(",", $ids)))->where("active", 1)->get();
    $raw_html = "";
    if (is_countable($user_list) && count($user_list)) {
        foreach ($user_list as $user_obj) {
            $user = $user_obj->name;
            $id = $user_obj->id;
            $image = $user_obj->get_user_image();
            $raw_html .= View::make("common.id_card", compact("user", "id","image"))->render();
        }
    }
    return view("templates.print_card", compact("raw_html"));
})->name("print_id_cards");
Route::get("print-barcode/{sbook_ids}", function ($sbook_ids) {
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    return view("templates.print_barcode", compact("generator", "sbook_ids"));
})->name("printing_barcode");


Route::get("verify-email", function () {
    if (!empty(request()->email) && !empty(request()->code)) {
        $sub = \App\Models\Subscriber::where("email", request()->email)->where("code", request()->code)->first();
        if ($sub) {
            $sub->active = 1;
            $sub->code = "";
            $sub->save();
            $title = __("subscriber.subs_success");
            return view("email_template.thank-you", compact("title"));
        }
    }
    abort(400);
});
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name("custom.logout");
Route::get("/", function () {
    if (!empty(Common::getSiteSettings("redirect_home_page"))) {
        return redirect(url(\App\Facades\Common::getSiteSettings("redirect_home_page")));
    }
    if (!Common::getSiteSettings("enable_frontend")) {
        abort(403, __("common.site_disabled_by_admin"));
    }
    $cust_title="";
    if (request()->hasAny(["search", "pcat", "scat"])) {
        if (request()->has("search")) {
            $cust_title = "Showing search result for the given keyword : " . request()->search;
        }
    }
    return view("front.index",compact("cust_title"));
});

Route::get('/dashboard', function () {
    $user = \App\Models\User::find(Auth::id());
    $roles = $user->roles;
    $roles_in_array = Common::getAllRoles()->pluck("name", "id")->toArray();
    $student_key = array_search("student", $roles_in_array);
    $super_admin = array_search("super admin", $roles_in_array);
    $admin = array_search("admin", $roles_in_array);
    if (in_array(Common::getDafaultRole(), $roles) && in_array($student_key, $roles) && true) {
        // wsetting.pl_web_icoe are student
        return view('back.user-dashboard');
    } else {
        // Admin Dashboard
        return view('back.dashboard');
    }

});


Route::resource("reports", ReportsController::class)->middleware("can:mng-report");
Route::resource("profile", ProfileController::class);
Route::resource("slider-mng", SliderController::class)->middleware("can:mng-slider");
Route::resource("user-mng", UserManagementController::class)->middleware("can:mng-user");
Route::resource("role-perm-mng", RolePermissionController::class)->middleware("can:mng-role-permission");
Route::resource("notice-mng", NoticeController::class)->middleware("can:mng-notice");
Route::resource("enquiry-mng", EnquiryController::class)->middleware("can:mng-enquiry");
Route::resource("subscriber-mng", SubscriberController::class)->middleware("can:mng-subscriber");
Route::get("subscribers-export", [SubscriberController::class, "exportSubs"])->name("subscriber-mng.exportSubs")->middleware("can:mng-subscriber");
Route::resource("notes-mng", NotesController::class)->middleware("can:mng-note");
Route::get("notes-mng/delete/{id}", [NotesController::class, "deleteNote"])->name("notes-mng.delete")->middleware("can:mng-note");
Route::resource("dashboard", DashboardController::class);
Route::resource("/classification-mng", DeweyDecimalClassController::class)->middleware("can:mng-classification");
Route::resource("/tag-mng", TagController::class)->middleware("can:mng-tag");
Route::resource("/author-mng", AuthorController::class)->middleware("can:mng-author");
Route::resource("/publisher-mng", PublisherController::class)->middleware("can:mng-publisher");
Route::resource("/course-year", CourseYearController::class)->middleware("can:mng-class");
Route::resource("/course", CourseController::class)->middleware("can:mng-class");
Route::resource("/year", YearController::class)->middleware("can:mng-class");
Route::resource("/books", BookController::class)->middleware("can:mng-book");
Route::post("/books-import", [BookController::class, "importBooks"])->name("books.import")->middleware("can:mng-book");
Route::post("/users-import", [UserManagementController::class, "importUser"])->name("users.import")->middleware("can:mng-user");
Route::get("/books-export", [BookController::class, "exportBooks"])->name("books.export")->middleware("can:mng-book");
Route::get("/transactions", [TransactionController::class, "index"])->name("transaction.index");
Route::resource("/sub-books", SubBookController::class)->middleware("can:mng-book");
Route::resource("/cycle-books", BorrowedController::class)->middleware("can:mng-book");
Route::resource("/lang-mng", LanguageTranslationController::class)->middleware("can:mng-translation");
Route::get("/issued-books", [BorrowedController::class, "indexReceiveBooks"])->name("indexReceiveBooks")->middleware("can:mng-book");
//Handles fine
Route::get("/pay-fine", [GatewayController::class, "finePay"])->name("gateway.pay_fine");
Route::get("/fine-receipt", [GatewayController::class, "fineReceipt"]);
Route::get("/refund-fine", [GatewayController::class, "refundFine"])->name("gateway.refund_fine");
//Json
Route::get("/get-user-ids", [SubBookController::class, "getUserIds"])->name("json.get_user_ids");
Route::get("/get-books-ids", [SubBookController::class, "getBookIds"])->name("json.get_book_ids");
Route::get("/get-borrowed-book-count", [SubBookController::class, "getUserBorrowedBookCount"])->name("json.get_borrowed_books_count");

Route::resource("setting", SettingController::class)->middleware("can:mng-setting");
Route::post('/clear-all', [SettingController::class, "clearCache"])->name("clear_cache")->middleware("can:mng-setting");
Route::post("/mng-db-bck", function (\Illuminate\Http\Request $request) {
    Artisan::call("backup:clean");
    session()->flash('form_setting', true);
    $path = storage_path() . "\bck_dbs\\" .
        Str::of(config('app.APP_NAME', 'laravel-backup'))->replace(" ", "-") . "\\";
    if ($request->do == "delete") {
        $file_loc = $path . $request->filename;
        if (file_exists($file_loc)) {
            unlink($file_loc);
        }
        Session::flash("alert-success", __("common.bck_db_del"));
        return redirect()->back()->with(["working_frm" => $request->frm_name]);
    }
    if ($request->do == "restore") {
        Util::unzip($path . $request->filename, storage_path("bck_dbs"));
        if (!Util::folderExist(storage_path("bck_dbs/db-dumps"))) {
            Session::flash("alert-error", __("common.restored_failed"));
            return redirect()->back()->with(["working_frm" => $request->frm_name]);
        }
        $sql_file = Util::getFilesInDir(storage_path("bck_dbs/db-dumps"));
        $rfile_loc = end($sql_file);

        $file_loc = storage_path("bck_dbs/db-dumps/") . $rfile_loc;
        $mysqlPath = "mysql";
        try {
            $command = "$mysqlPath -u " . config('app.DB_USERNAME');
            if (!empty(config('app.DB_PASSWORD'))) {
                $command .= " -p" . trim(config('app.DB_PASSWORD'));
            }
            $command .= " -h " . config('app.DB_HOST') . " " . config('app.DB_DATABASE') . "  < " . $file_loc . "  2>&1 && exit";
            $returnVar = NULL;
            $output = NULL;
            exec($command, $output, $returnVar);
        } catch (Exception $e) {
            Session::flash("alert-error", strval($e));
            return redirect()->back()->with(["working_frm" => $request->frm_name]);
        }
        Session::flash("alert-success", __("common.bck_restored"));
        // Lets unlink the file and directory
        unset($file_loc);
        Util::deleteDir(storage_path("bck_dbs\db-dumps"));
        return redirect()->back()->with(["working_frm" => $request->frm_name]);
    }
    if ($request->do == "backup") {
        \Illuminate\Support\Facades\Artisan::call('backup:run');
        Session::flash("alert-success", __("common.bck_created"));
        return redirect()->back()->with(["working_frm" => $request->frm_name]);
    }
})->name("file.db_mng")->middleware("can:mng-setting");
Route::get("/{page_slug}", function ($page_slug) {
    $mapper = ["terms-and-conditions" => "toi", "privacy-policy" => "pp"];
    if (in_array($page_slug, array_keys($mapper))) {
        $slug = $page_slug;
        $title = "";
        $desc = "";
        if ($mapper[$page_slug] == "toi") {
            $title = Common::getSiteSettings("toi_heading");
            $desc = Common::getSiteSettings("toi_desc");
        }
        if ($mapper[$page_slug] == "pp") {
            $title = Common::getSiteSettings("pp_heading");
            $desc = Common::getSiteSettings("pp_desc");
        }
        return view("templates.basic", compact("title", "slug", "desc"));
    }
});
Route::get('/details/{page_slug}', function ($page_slug) {

    $slug_holder = array();
    $slugs = \App\Models\Book::all()->pluck("title", "id")->each(function ($value, $key) use (&$slug_holder) {
        return $slug_holder[$key] = UTF8::cleanup(Common::utf8Slug($value));
    });
    $fn_key = array_search(UTF8::cleanup($page_slug), $slug_holder);
    if ($fn_key) {
        $book_obj = Book::with("sub_books")->where("id", $fn_key)->first();
        $note_obj = \App\Models\Notes::where("book_id", $book_obj->id)->where("note_status", 1)->get();
        return view("templates.book_detail", compact("book_obj", "note_obj"));
    } else {
        abort(404, __("common.page_not_found"));
    }


});
