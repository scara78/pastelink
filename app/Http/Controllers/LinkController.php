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

    // source: https://gist.github.com/jasny/2000705
    /**
     * Turn all URLs in clickable links.
     * 
     * @param string $value
     * @param array  $protocols  http/https, ftp, mail, twitter
     * @param array  $attributes
     * @return string
     */
    public function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
        }
        
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
        
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$protocol://$link</a>") . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }
        
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
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
    		'content' => $this->linkify($link->content,array('http', 'mail'),['target'=>'_blank']),
    		'views' => $link->views,
    		'date' => \Carbon\Carbon::parse($link->created_at)->format('d, M Y'),
    		'password' => $link->password
    	];
    }
}
