@extends('layouts.app')

@section('content')

    <div class="controls-products">
        <form class="navbar" action="{{ route('productos.index') }}">
            <i class="fa fa-search"></i>
            <input name="busqueda" type="text" value="{{ $busqueda }}">
        </form>

        <a class="btn" href="{{route('productos.create')}}">
            <i class="fa fa-add"></i> Añadir
        </a>
    </div>

    <div class="list-products">
        @foreach($productos as $producto)
            <article class="card">
                @if($producto->imagen_url == null)
                <div class="card-image card-image-not-found"></div>
                @else
                    <div class="card-image">
                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                    </div>
                @endif
                <div class="card-info">
                    <h1 class="card-title" title="{{ $producto->nombre }}">{{ $producto->nombre }}</h1>
                    <p class="card-price">${{ number_format($producto->precio, 2) }}</p>
                    <p class="card-brand">{{ $producto->marca->nombre }}</p>
                </div>
                <div class="card-actions">
                    <a class="btn-action btn-action-primary" href="{{route('productos.edit', ['producto' => $producto->id])}}"><i class="fa fa-pencil"></i></a>
                    <form action="{{route('productos.destroy', $producto->id)}}"  method="POST" >
                        @csrf
                        {{ method_field('DELETE') }}
                        <input class="btn-action btn-action-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" value="🗑️">
                    </form>
                </div>
            </article>
        @endforeach
    </div>
    <div class="pagination">
        {{ $productos->appends($_GET)->links() }}
    </div>
@endsection
