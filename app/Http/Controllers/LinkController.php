<?php

namespace App\Http\Controllers;

use App\{Link, History};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LinkRequest;
use Carbon\Carbon;

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
    	// add views value for every visitor
    	$link->increment('views');

    	// get and filter the latest text
    	$links = Link::where([['visibility', '=', 0], ['id', '!=', $link->id]])->latest()->limit(8)->get();

    	// show the cpntent
    	return view('index', $this->getLinkData($link, $links));
    }

    /**
    * Generate new link and store it into database
    *
    * @param array $request
    * @param model $link 
    */
    public function store(LinkRequest $request, Link $link)
    {
    	// random string for url
    	$random = $this->find_random();

    	// get all requests
    	$data = $request->all();
    	
    	// assign additional information
    	$data['slug'] = (isset($request->custom_url)) ? \str::slug($request->custom_url) : $random;
    	$data['title'] = (isset($request->title)) ? $request->title : 'Untitled';
    	$data['password'] = (isset($request->password)) ? bcrypt($request->password) : '';
    	$data['visibility'] = ($request->visibility == 'hidden') ? 1 : 0;

    	// check again if slug is exists or not
    	if(Link::where('slug', $data['slug'])->exists())
    	{
    		// sent to previous page when slug is exists
    		session()->flash('url_exists', 'The URL already exists.');
    		return redirect()->back();
    	}

    	// everything is okay and ready
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
    	return view('edit', [
            'link' => $link,
            'histories' => $link->history,
            'latest_hash' => $link->history()->latest()->limit(1)->get()
        ]);
    }

    public function update(LinkRequest $request, link $link, History $history)
    {
    	// checking the password & id before update it
    	if(!Hash::check(session('pass'), $link->password) && session('id') == $link->id)
    		return abort(403);

    	// get all data request
    	$data = $request->all();

        $data_history = array(
            'link_id' =>$link->id,
            'hash' => md5(\Str::random())
        );

        $history->create($data_history);

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
    * @return string
    */
    public function find_random()
    {
    	// generate new random string
    	$random = \Str::random(6);

    	// checking if slug is exists
    	while(Link::where('slug', $random)->exists()){
    		// generate again until available
    		$random = \Str::random(6);
    	}

    	return $random;
    }

    public function getLinkData($link, $links = array())
    {
    	return [
    		'id' => $link->id,
    		'title' => $link->title,
    		'content' => $link->content,
    		'views' => $link->views,
    		'date' => Carbon::parse($link->created_at)->format('d, M Y'),
    		'password' => $link->password,
    		'links' => $links
    	];
    }
}
