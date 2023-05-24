@extends('layouts.admin')

@section('content')

<h1>Crea un progetto</h1>

<form action="{{route('admin.projects.store')}}" method="POST">

    @csrf

    <div class="mb-3">
        <label for="">Titolo</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title')}}">
        
        @error('title')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>

    <div class="mb-3">
        <label for="type_id">Tipologia</label>
        {{-- name deve chiamarsi type_id per la corrispondenza nella tabella --}}
        <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">

            {{-- opzione nulla --}}
            <option value="">Nessuna</option>

            {{-- ciclo per ogni tipologia contenuta nella tabella types --}}
            @foreach ($types as $type)
                <option value="{{$type->id}}" {{$type->id == old('type_id') ? 'selected' : ''}}>{{$type->name}}</option>
            @endforeach


        </select>

        @error('type_id')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror

    </div>

    <div class="mb-3">
        <label for="">Contenuto</label>
        <textarea name="content" id="content" cols="30" rows="10" class="form-control @error('content') is-invalid @enderror">{{old('content')}}</textarea>

        @error('content')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>

    <button class="btn btn-primary" type="submit">Aggiungi</button>
</form>
    
@endsection