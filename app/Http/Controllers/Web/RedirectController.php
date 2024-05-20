<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect(Redirect $redirect)
    {
        return redirect()->to($redirect->url);
    }

    public function redirectWithSubfolder(string $subfolder, string $redirect)
    {
        $redirect = Redirect::query()->where('subfolder', $subfolder)->where('hash', $redirect)->firstOrFail();

        return redirect()->to($redirect->url);
    }
}
