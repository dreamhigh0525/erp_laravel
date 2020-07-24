<?php
namespace App\Helpers;

use App\Models\Accounting\account_account;
use App\Models\Accounting\account_journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Accounting {
    public static function account_account() {
        $account = account_account::orderBy('code','asc')->get();
        return $account;
    }

    public static function account_journal() {
        $journal = account_journal::orderBy('code','asc')->get();
        return $journal;
    }
}