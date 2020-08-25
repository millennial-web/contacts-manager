@extends('layouts.app')

@section('title', 'Process Contacts')

@section('content')

    <p>{{ session('status') }}</p>

    <form method="POST" action="{{ url("save-contacts") }}" enctype="form-data">
        {{ csrf_field() }}
        {{-- hidden inputs for column names --}}
        @for($i=0; $i < count($columns); $i++)
            <input type="hidden" name="columns[]" value="{{ str_replace(" ","_", strtolower($columns[$i] ) ) }}"/>
        @endfor()

        <h2>Please Verify Contacts Data to Import</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th colspan="4" class="text-center border bg-primary text-light">
                        Standard attributes
                    </th>
                    <th colspan="{{ count( array_slice($contacts[0], 2) ) }}" class="text-center border bg-secondary text-light">
                        Custom attributes
                    </th>
                </tr>
                <tr>
                    {{-- contacts fields --}}
                    @for($i=0; $i < 4; $i++)
                        <th>{{$columns[$i]}} {{ in_array($columns[$i],['phone','team_id','email']) ? ' *' : '' }} </th>
                    @endfor()
                    {{-- custom attributes --}}
                    @for($i=4; $i < count($columns); $i++)
                        <td>{{$columns[$i]}}</td>
                    @endfor()
                </tr>
                
                @foreach ($contacts as $key => $row)
                    <tr>
                        @for($i=0; $i<count($row); $i++)
                            @if($i == 0)
                                <td>
                                    <select name="contacts_team_id[]" class="form-control border border-primary" required>
                                        <option value="1" {{ $row[$i] == 1 ? 'selected' : '' }}>Alpha</option>
                                        <option value="2" {{ $row[$i] == 2 ? 'selected' : '' }}>Beta</option>
                                        <option value="3" {{ $row[$i] == 3 ? 'selected' : '' }}>Charlie</option>
                                        <option value="4" {{ $row[$i] == 4 ? 'selected' : '' }}>Delta</option>
                                    </select>
                                </td>
                            @else()
                                <td>
                                    <input type="text" class="form-control  {{ in_array($columns[$i],['phone','team_id','email']) ? 'border border-primary' : '' }}" name="contacts_{{ $columns[$i] }}[]" value="{{ $row[$i] }}" {{ $columns[$i] == 'phone' ? 'required' : '' }}>
                                </td>
                            @endif()
                        @endfor()
                    </tr>
                @endforeach
            </tbody>            
        </table>
            
        <p>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Looks Good!</button>
        </p>

    </form>

@endsection