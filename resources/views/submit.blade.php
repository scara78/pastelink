@extends('layouts.app')

@section('title', '- Submit')

@section('content')
<form action="{{ route('submit.store') }}" method="post" class="col-xl-12">
<div class="row mt-5">
	<div class="col-md-8 mb-2">
		<div class="card">
			<div class="card-body">
				<input type="text" class="form-control" placeholder="Title" name="title">
				<label for="content"></label>
				<textarea name="content" id="" cols="30" rows="15" class="form-control" placeholder="Text or Links"></textarea>
				<div class="text-danger mt-2">
					@error('content')
						{{ $message }}
					@enderror
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="card-title text-center"><h5>Options</h5></div>
				{{-- custom url option --}}
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<input type="checkbox" aria-label="Checkbox for enabled" class="toggle-option">
						</div>
					</div>
					<input type="text" class="form-control" name="custom_url" placeholder="Custom URL" id="url" disabled>
				</div>
				@if(session()->has('url_exists'))
				<div class="text-danger">
					{{ session()->get('url_exists') }}
				</div>
				@endif
				<div class="text-secondary text-sm">
					eg: abc will be -> {{ config('app.url') }}/abc
				</div>
				{{-- password option --}}
				<div class="input-group mt-2">
					<div class="input-group-prepend">
						<div class="input-group-text">
							<input type="checkbox" aria-label="Checkbox for enabled" class="toggle-option2">
						</div>
					</div>
					<input type="text" class="form-control" name="password" placeholder="Password" id="pass" disabled>
				</div>
				<div class="text-secondary mb-2">
					For editable text
				</div>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">Visibility</span>
					</div>
					<select name="visibility" id="" class="form-control">
						<option value="public">Public</option>
						<option value="hidden">Hidden</option>
					</select>
				</div>
			</div>
		</div>
		@csrf
		<div class="text-center">
			<button type="reset" value="" class="btn btn-outline-danger mt-2 mr-2">Reset</button> <button type="submit" class="btn btn-outline-primary mt-2">Publish</button>
		</div>
	</div>
</div>
</form>
@endsection

@section('script')
<script>
	$('.toggle-option').click(function(){
		if($(this).prop('checked')){
			$('#url').prop('disabled', false);
		}
		else {
			$('#url').prop('disabled', true);
		}
	});

	$('.toggle-option2').click(function(){
		if($(this).prop('checked')){
			$('#pass').prop('disabled', false);
		}
		else {
			$('#pass').prop('disabled', true);
		}
	});
</script>
@endsection