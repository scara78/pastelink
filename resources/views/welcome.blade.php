@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Paste links and text together</h1>
    <div class="text-secondary mb-3">
        create and publish plain text containing links.
    </div>
    <div class="mt-5">
    	<a href="{{ route('submit') }}" class="btn-started">get started</a>
    </div>
    
</div>
@endsection