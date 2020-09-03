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
				<div class="h5 text-danger">{{ $latest_hash[0]->hash }}</div>
				<div class="text-right mb-2">
					<button type="button" class="btn btn-info btn-sm"><i class="fas fa-clock"></i> History</button>
				</div>
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

	<!-- Modal -->
	<div class="modal fade" id="modal-history" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Edit history</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div class="table-responsive">
		      	<table class="table table-striped">
		        	<thead>
		        		<th>Hash</th>
		        		<th>Time</th>
		        	</thead>
		        	<tbody>
		        		@foreach($histories as $history)
		        		<tr>
			        		<td>{{ $history->hash }}</td>
			        		<td>{{ $history->created_at }}</td>
		        		</tr>
		        		
		        		@endforeach
		        	</tbody>
		        </table>
		      </div>
	      	</div>
	        
	      <div class="modal-footer">
	        
	      </div>
	    </div>
	  </div>
	</div>

</div>
</form>
@endsection

@section('script')
<script>
	$('.btn.btn-info.btn-sm').click(function(){
		$('#modal-history').modal('show');
	});
</script>
@endsection