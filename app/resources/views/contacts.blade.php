@extends('layouts.app')

@section('title', 'My Contacts')

@section('content')
    <div class="text-center p-2">
        @if(session('status'))
            <div class='alert alert-success'>
                {{ session('status') }}
            </div>
            <hr>
        @endif 
        <a href="/testcontactsimport.csv" class="btn btn-success">
            <i class="fa fa-file"></i> Download Example CSV
        </a>
        <a href="import-contacts" class="btn btn-success">
            <i class="fa fa-rocket"></i> Import Contacts
        </a>
    </div>

    <h2 for="file" class="control-label mt-3">
        Contacts List
    </h2>

    @if( count($contacts) )
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th colspan="4" class="text-center border bg-primary text-light">
                        Standard attributes
                    </th>
                    <th colspan="{{ $contacts[0]->custom_attributes_count }}" class="text-center border bg-secondary text-light">
                        Custom attributes
                    </th>
                </tr>
                <tr>
                    <th>Team</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    @foreach($contacts[0]->custom_attributes as $attr)
                        <th>{{$attr->key}}</th>
                    @endforeach
                </tr>

                @foreach($contacts as $contact)
                    <tr>
                        <td>
                            {{ $contact->team_name }}
                        </td>
                        <td>
                            {{ $contact->name }}
                        </td>
                        <td>
                            {{ $contact->phone }}
                        </td>
                        <td>
                            {{ $contact->email }}
                        </td>
                        @foreach($contact->custom_attributes as $attr)
                            <td>{{$attr->value}}</td>
                        @endforeach
                    </tr>
                @endforeach

            </tbody>
            <div class="d-flex justify-content-center">
                {!! $contacts->links() !!}
            </div> 
        </table>
    @else 
        <h4>You have no contacts</h4>
    @endif
@endsection






