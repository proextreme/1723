<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Public sections — full pages land in a later slice; the routes exist now so
// navigation and the footer resolve.
Route::view('/fashion', 'placeholder', ['heading' => 'Fashion'])->name('fashion');
Route::view('/print', 'placeholder', ['heading' => 'Print'])->name('print');
Route::view('/submit', 'placeholder', ['heading' => 'Submit'])->name('submit');
Route::view('/partnerships', 'placeholder', ['heading' => 'Partnerships'])->name('partnerships');

Route::view('/privacy-policy', 'placeholder', ['heading' => 'Privacy Policy'])->name('legal.privacy');
Route::view('/cookie-policy', 'placeholder', ['heading' => 'Cookie Policy'])->name('legal.cookies');
Route::view('/terms', 'placeholder', ['heading' => 'Terms & Conditions'])->name('legal.terms');
Route::view('/faq', 'placeholder', ['heading' => 'FAQ'])->name('faq');

Route::post('/newsletter', function () {
    // Wired to the NewsletterSubscriber boundary in a later slice.
    return back()->with('newsletter_status', 'Thanks — we will be in touch.');
})->middleware('throttle:6,1')->name('newsletter.store');
