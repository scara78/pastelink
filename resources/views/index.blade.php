@extends('layouts.app')

@section('title', '- '.$title)

@section('content')
<div class="row mt-5">
	<div class="col-md-8 mb-3">
		@if(session()->has('error'))
		<div class="alert alert-danger">
			{{ session()->get('error') }}
		</div>
		@endif
		<div class="card">
			<div class="card-body">
				<h3>{{ $title }}</h3>
				<div class="text-secondary text-right mb-2">
					<i class="fas fa-eye"></i> views: {{ $views }}  <i class="fas fa-calendar ml-3"></i> {{ $date }}
				</div>
				<div class="box-content">
					{!! Purifier::clean(nl2br($content)) !!}
				</div>
				@if($password != '')
				<form action="{{ route('auth') }}" method="post" class="mt-3">
					<button type="button" class="btn btn-outline-secondary mb-2 edit">Edit</button>
					<div class="form-group" id="login">
						@csrf
						<input type="hidden" name="id" value="{{ $id }}">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control mb-2">
						<button type="submit" class="btn btn-outline-danger">Enter</button>
					</div>
					
				</form>
				@endif
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="card-title text-center">Advertisement</div>
					</div>
				</div>
			</div>
		</div>
	
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header bg-dark text-light">Latest</div>
			<div class="card-body">
				<ul class="list-group list-group-flush">
					@foreach($links as $link)
					<li class="list-group-item">
						<a href="/{{ $link->slug }}">{{ $link->title }}</a>
						<div class="text-secondary">{{ $link->created_at->diffForHumans() }}</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function(){
		$('#login').toggle();
	});

	$('.edit').click(function(){
		$('#login').toggle();
	})
</script>
@endsection