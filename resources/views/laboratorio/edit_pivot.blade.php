@extends('layouts.app')

@section('title', 'Editar Inventario: '.$producto->nombre.' en '.$laboratorio->nombre)

@section('content')
<div class="container-fluid">
    <h1>Editar Inventario para {{ $producto->nombre }}</h1>
    <h5 class="text-muted">Laboratorio: {{ $laboratorio->nombre }}</h5>

    <div class="card">
        <div class="card-body">

            {{-- La ruta de actualización ahora usa $pivotRecord->id --}}
            <form method="POST" action="{{ route('laboratorio.producto.update_pivot', [$laboratorio->id, $pivotRecord->id]) }}">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Costo Análisis (Fecha) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Costo Análisis</label>
                        <input type="number" name="costo_analisis" class="form-control @error('costo_analisis') is-invalid @enderror"
                            value="{{ old('costo_analisis', $pivotRecord->costo_analisis) }}" />
                        @error('costo_analisis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tiempo Entrega (Fecha) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tiempo Entrega Días</label>
                        <input type="number" name="tiempo_entrega_dias" class="form-control @error('tiempo_entrega_dias') is-invalid @enderror"
                            value="{{ old('tiempo_entrega_dias', $pivotRecord->tiempo_entrega_dias) }}" />
                        @error('tiempo_entrega_dias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Entrega</label>
                        <input type="date" name="fecha_entrega" class="form-control @error('fecha_entrega') is-invalid @enderror" value="{{ old('fecha_entrega', $pivotRecord->fecha_entrega) }}" />
                        @error('fecha_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar Inventario</button>
                    <a href="{{ route('laboratorio.show', $laboratorio->id) }}" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection