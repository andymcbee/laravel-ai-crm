<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Models\Contact;
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

    return Inertia::render('Dashboard', [
        'total_contacts' => $totalContacts,
        'new_contacts' => $newContacts,
        'contacts_by_company' => $contactsByCompany,
        'recent_contacts' => $recentContacts,
        'contacts_by_month' => $contactsByMonth,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
