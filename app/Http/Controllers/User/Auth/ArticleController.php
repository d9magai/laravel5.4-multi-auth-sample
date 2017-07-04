<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'url' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function store()
    {
	$rules = [
		'title' => 'required',
		'url'=>'required',
		'description' => 'required',
	];
	$messages = [
		'title.required' => 'タイトルを正しく入力してください。',
		'url.required' => 'urlを正しく入力してください。',
		'descriptin.required' => '本文を選択してください。',
	];
	$validator = Validator::make(Input::all(), $rules, $messages);
	if ($validator->passes()) {
            $link = new \App\Link;
            $link->title = Input::get('title');
            $link->url = Input::get('url');
            $link->description = Input::get('description');
            $link->save();
            return Redirect::back()->with('message', '投稿が完了しました。');
	}else{
		return Redirect::back()
			->withErrors($validator)
			->withInput();
	}
    }

    public function index()
    {
        return view('auth.submit');
    }

    protected function guard()
    {
        return Auth::guard('user');
    }
}
