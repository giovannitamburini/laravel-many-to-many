@extends('layouts/admin')

@section('content')

<div class="container py-3">

    <h1>Tecnologie</h1>

    <table class="table table-striped">
        <th>Nome</th>
        <th>Slug</th>
        {{-- <th>N° progetti</th> --}}
        <th></th>
        
        <tbody>

            @foreach ($technologies as $technology)
                
            <tr>
                <td>{{$technology->name}}</td>
                <td>{{$technology->slug}}</td>
                {{-- <td>{{count($technologies->)}}</td> --}}
                <td>
                    {{-- <a href="{{route('admin.technology.show', $technology)}}"><i class="fa-solid fa-magnifying-glass"></i></a> --}}
                    <a href=""></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-around mb-5">
        <a href="{{route('admin.technologies.create')}}" class="btn btn-primary">
            Aggiungi una tecnologia
        </a>
    </div>

</div>
    
@endsection