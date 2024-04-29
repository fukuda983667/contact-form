<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('index');
    }

    // バリデーションルールに適合しなかった場合、デフォルトではリダイレクトする。クライアントがリクエストしたときのページに。
    public function confirm(ContactRequest $request)
    {
        $contact = $request->only(['name', 'email', 'tel', 'content']);
        return view('confirm', ['contact' => $contact]);
    }

    // thanksページでリダイレクトすると同じ内容のデータが追加される不具合がある。
    public function store(ContactRequest $request)
    {
        $contact = $request->only(['name', 'email', 'tel', 'content']);
        Contact::create($contact);
        return view('thanks');
    }
}
