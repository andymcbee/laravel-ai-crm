<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Note;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;



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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    // Hardcoded data simulation
    $totalContacts = Contact::count();
    $newContacts = Contact::whereMonth('created_at', Carbon::now()->month)->count();

    $contactsByCompany = Contact::selectRaw('company, COUNT(*) as count')
        ->groupBy('company')
        ->pluck('count', 'company');

    $recentContacts = Contact::latest()->take(5)->get(['id', 'first_name', 'last_name', 'created_at']);

    $contactsByMonth = Contact::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->pluck('count', 'month');




    // TODO: ADD ACCOUNT ID

    $startCurrent30 = Carbon::now()->subDays(30); // 30 days ago
    $startPrevious30 = Carbon::now()->subDays(60); // 60 days ago (to get the previous 30 days window)
    // new contacts
    $contactCurrent30DaysCount = Contact::where('created_at', '>=', $startCurrent30)->count();
    $contactPrevious30DaysCount = Contact::whereBetween('created_at', [$startPrevious30, $startCurrent30])->count();

    $contactDifference = $contactCurrent30DaysCount - $contactPrevious30DaysCount;
    $contactPercentageChange = $contactPrevious30DaysCount > 0
        ? round(($contactDifference / $contactPrevious30DaysCount) * 100, 2)
        : 100; // Assume 100% growth if no previous data


    // new notes

    $noteCurrent30DaysCount = Note::where('created_at', '>=', $startCurrent30)->count();
    $notePrevious30DaysCount = Note::whereBetween('created_at', [$startPrevious30, $startCurrent30])->count();

    $noteDifference = $noteCurrent30DaysCount - $notePrevious30DaysCount;

    $notePercentageChange = $notePrevious30DaysCount > 0
        ? round(($noteDifference / $notePrevious30DaysCount) * 100, 2)
        : 100;



    // total contacts

    // add account id
    $totalContactCount = Contact::count();


    // users with contact count 30 days

    // TODO add 30 day period
    $users = Account::find(1)->users()
        ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
        ->withCount('notes', 'contacts')
        ->get();

    $users = $users->map(function ($user) {
        return [
            'firstName'  => $user->first_name,
            'lastName'   => $user->last_name,
            'email'      => $user->email,
            'notesCount' => $user->notes_count,
            'contactsCount' => $user->contacts_count,
        ];
    });



    // test


    //$user = User::withCount('notes')->find(1);
   // dd($user->notes_count);




    return Inertia::render('Dashboard', [
        'new_contact_stats' => [
            'last_30_days' => $contactCurrent30DaysCount,
            'percent_change' => $contactPercentageChange,
        ],
        'new_note_stats' => [
            'last_30_days' => $noteCurrent30DaysCount,
            'percent_change' => $notePrevious30DaysCount,
        ],
        'total_contact_count' => $totalContactCount,
        'users' => $users,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// TODO either move profiles to own middleware group, or ensure account_id is factored in
Route::middleware(['auth', 'active.account'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//    Route::get('/contacts', function () {
//        return Inertia::render('Contacts');
//    })->name('contacts');

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');


    // notes

    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');


    //




    Route::post('/account/switch', function (Request $request) {

        $accountId = $request->input('account_id');

        $user = Auth::user();

        if(!$user->accounts()->where('accounts.id', $accountId)->exists()) {
            return response()->json([
                'errors' => 'unauthorized'
            ],403);
        }

        Session::put('active_account', $user->accounts()->find($accountId));

        return back();

    });



});






require __DIR__.'/auth.php';
