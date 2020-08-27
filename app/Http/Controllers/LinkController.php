<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LinkRequest;

class LinkController extends Controller
{
	// show submit menu
    public function index()
    {
    	return view('submit');
    }

    // show the content for each links
    public function get(link $link)
    {
    	$link->increment('views');
    	return view('index', $this->getLinkData($link));
    }

    /**
    * Generate new link and store it into database
    *
    * @param array $request
    * @param model $link 
    */
    public function store(Request $request, Link $link)
    {
    	// validate request
    	$request->validate([
    		'content' => 'required|min:5'
    	]);

    	// random string for url
    	$random = $this->find_random($link);

    	// get all requests
    	$data = $request->all();
    	
    	// assign slug from custom or random
    	$data['slug'] = (isset($request->custom_url)) ? \str::slug($request->custom_url) : $random;
    	// assign title
    	$data['title'] = (isset($request->title)) ? $request->title : 'Untitled';
    	$data['password'] = (isset($request->password)) ? bcrypt($request->password) : '';

    	// insert into database
    	$link->create($data);

    	// redirect to result link
    	return redirect()->to(route('get', $data['slug']));
    }

    /**
    * Authentication for edit menu
    *
    * @param array $request
    * @param model $link
    * @return redirect to edit menu or sent back to previous page
    */
    public function auth(Request $request, Link $link)
    {
    	// input id & password from request to session
    	session([
    		'id' => $request->id,
			'pass' => $request->password
		]);

        // redirect to edit page if password is valid
        return redirect()->to(route('edit', Link::find(session('id'))->slug));
    }

	/**
    * Show edit menu
    *
    * @param model $link
    * @return show edit menu or sent the error code
    */
    public function edit(Link $link)
    {
    	// show edit menu
    	return view('edit', compact('link'));
    }

    public function update(LinkRequest $request, link $link)
    {
    	// checking the password & id before update it
    	if(!Hash::check(session('pass'), $link->password) && session('id') == $link->id)
    		return abort(403);

    	// get all data request
    	$data = $request->all();

    	// update into database
    	$link->update($data);

    	// sent message success
    	session()->flash('success', 'The changes have been saved!');
    	// redirect to previous page
    	return redirect()->back();
    }

    /**
    * Find the random string for slug
    *
    * @param model $model
    * @return string
    */
    public function find_random($model)
    {
    	$random = \Str::random(6);

    	while($model->where('slug', $random)->exists()){
    		$random = \Str::random(6);
    	}
    	return $random;
    }

    public function getLinkData($link)
    {
    	return [
    		'id' => $link->id,
    		'title' => $link->title,
    		'content' => $link->content,array('http', 'mail'),['target'=>'_blank'],
    		'views' => $link->views,
    		'date' => \Carbon\Carbon::parse($link->created_at)->format('d, M Y'),
    		'password' => $link->password
    	];
    }
}
