@extends('layouts.admin')

@section('content')

<h1>Modifica il progetto</h1>

<form action="{{route('admin.projects.update', $project)}}" method="POST">

    @csrf

    @method('PUT')

    {{-- TITOLO --}}
    <div class="mb-3">
        <label for="">Titolo</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title') ?? $project->title}}">
        @error('title')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>

    {{-- TIPOLOGIA --}}
    <div class="mb-3">
        <label for="type_id">Tipologia</label>
        {{-- name deve chiamarsi type_id per la corrispondenza nella tabella --}}
        <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">

            {{-- opzione nulla --}}
            <option value="">Nessuna</option>

            {{-- ciclo per ogni tipologia contenuta nella tabella types --}}
            @foreach ($types as $type)
                <option value="{{$type->id}}" {{$type->id == old('type_id',$project->type_id) ? 'selected' : ''}}>{{$type->name}}</option>
            @endforeach


        </select>

        @error('type_id')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror

    </div>

    {{-- TECNOLOGIA --}}
    <div class="mb-3 form-group">
        <h4>Tecnologie</h4>

        {{-- ciclo per ogni elemento contenuto in technologies --}}
        @foreach ($technologies as $technology)

        <div class="form-check">
            
            {{-- nel name ci devo inserire il nometabella[], in questo caso technologies[], perchè gli passo un array --}}
            {{-- nel value ci inserisco l'id di tech perchè sarà quello che effettivamente inserirò nella tabella ponte --}}
            {{-- nel id ci inserisco  l'id di tech e come prefisso il nome più il trattino--}}
            <input type="checkbox" id="technology-{{$technology->id}}" name="technologies[]" value="{{$technology->id}}">
            <label for="technology-{{$technology->id}}">{{$technology->name}}</label>

        </div>
            
        @endforeach

    </div>

    {{-- CONTENUTO --}}
    <div class="mb-3">
        <label for="">Contenuto</label>
        <textarea name="content" id="content" cols="30" rows="10" class="form-control @error('content') is-invalid @enderror">{{old('content') ?? $project->content}}</textarea>

        @error('content')

        <div class="invalid-feedback">
            {{$message}}
        </div>
            
        @enderror
    </div>

    {{-- BITTON SUBMIT --}}
    <button class="btn btn-primary" type="submit">Modifica</button>
</form>
    
@endsection