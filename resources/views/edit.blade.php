@extends('layouts.app')

@section('title', '- Submit')

@section('content')
<form action="{{ route('edit.update', $link->slug) }}" method="post" class="col-xl-12">
<div class="row mt-5">
	<div class="col-md-8">
		@if(session()->has('success'))
		<div class="alert alert-success">
			{{ session()->get('success') }}
		</div>
		@endif
		<div class="card">
			<div class="card-body">
				<input type="text" class="form-control" placeholder="Title" name="title" value="{{ old('title') ?? $link->title }}">
				<label for="content"></label>
				<textarea name="content" id="" cols="30" rows="15" class="form-control" placeholder="Text or Links">{{ old('content') ?? $link->content }}</textarea>
				<div class="text-danger mt-2">
					@error('content')
						{{ $message }}
					@enderror
				</div>
				@csrf
				@method('patch')
				<div class="text-center">
					<button type="reset" value="" class="btn btn-outline-danger mt-2 mr-2">Reset</button> <button type="submit" class="btn btn-outline-primary mt-2">Update</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="card-title text-center">Advertisement</div>
			</div>
		</div>
	</div>
</div>
</form>
@endsection