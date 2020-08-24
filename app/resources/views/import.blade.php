@extends('layouts.app')

@section('title', 'Import Contacts')

@section('content')
    @if(session('status'))
        <h2>{{ session('status')}} </h2>
        <hr>
    @endif 

    @if($errors)
        @foreach ($errors->all() as $message)
            <div class="alert alert-danger"> {{$message}} </div>
        @endforeach
    @endif

    <form method="POST" action="{{ url("process-contacts") }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
            <h3 for="file" class="control-label mt-3">
                Select CSV file to import
            </h3>
            
            <input id="file" type="file" class="form-control" name="file" style="padding:20px 20px 50px 20px;" required>

            @if ($errors->has('file'))
                <span class="help-block">
                    <strong>{{ $errors->first('file') }}</strong>
                </span>
            @endif
        </div>
    
        <p>
            <button type="submit" class="btn btn-success" name="submit">
            <i class="fa fa-rocket"></i> Import Contacts</button>
        </p>

    </form>
@endsection






