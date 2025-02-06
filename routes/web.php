<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/send-test-email', function () {
    Mail::to('myomayouth1225@gmail.com')->send(new TestMail());
    return "Test email has been sent!";
});
