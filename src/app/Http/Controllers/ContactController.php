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

    // thanksページでページの再読み込みすると同じ内容のデータが追加される不具合がある。
    public function store(ContactRequest $request)
    {
        // セッションを再生成している。これにより新しいcsrfトークンがフォームを含むページをクライアントに
        // 送信したときに一緒に送信される。古いトークンは認証できなくなる。
        // 再読み込みでform送信しても古いcsrfトークンでリクエストを送っているため認証されない。
        // 送信ボタン連打も対策できる。一回目のリクエスト処理するときにセッションが更新され、
        // クライアントが再度新しいcsrfトークンを得るにはフォームを含むページに移動する必要がある。
        // 前のページに戻って再度送信ボタンを押される場合も対策できる。
        $request->session()->regenerateToken();

        $contact = $request->only(['name', 'email', 'tel', 'content']);
        Contact::create($contact);
        return redirect()->route('thanks');
    }

    // thanksページはgetでしかリクエストを送れないため、再読み込みやリダイレクトでデータを追加することはない。
    public function thanks()
    {
        return view('thanks');
    }
}
