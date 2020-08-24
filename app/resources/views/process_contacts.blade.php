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
                    <th colspan="4" class="text-center border bg-dark text-light">
                        Standard attributes
                    </th>
                    <th colspan="{{ count( array_slice($contacts[0], 3) ) }}" class="text-center border bg-dark text-light">
                        Custom attributes
                    </th>
                </tr>
                <tr>
                    {{-- contacts fields --}}
                    <th>team *</th>
                    @for($i=0; $i < 3; $i++)
                        <th>{{$columns[$i]}} {{ $columns[$i] == 'phone' ? ' *' : '' }} </th>
                    @endfor()
                    {{-- custom attributes --}}
                    @for($i=3; $i < count($columns); $i++)
                        <td>{{$columns[$i]}}</td>
                    @endfor()
                </tr>
                
                @foreach ($contacts as $key => $row)
                    <tr>
                        <td>
                            <select name="contacts_team_id[]" class="form-control border border-primary" required>
                                <option value="1">Alpha</option>
                                <option value="2">Beta</option>
                                <option value="3">Charlie</option>
                                <option value="4">Delta</option>
                            </select>
                        </td>
                        @for($i=0; $i<count($row); $i++)
                            <td>
                                <input type="text" class="form-control  {{ $columns[$i] == 'phone' ? 'border border-primary' : '' }}" name="contacts_{{ $columns[$i] }}[]" value="{{ $row[$i] }}" {{ $columns[$i] == 'phone' ? 'required' : '' }}>
                            </td>
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