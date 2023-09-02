<?php

namespace App\Http\Controllers;

use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseTestController extends Controller
{
    private $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    public function loginAnonymous()
    {
        $anonymous = $this->auth->signInAnonymously();

    }
}
