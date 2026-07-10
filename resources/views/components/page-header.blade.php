@props([
    'title' => 'ViceRectorado Académico',
    'breadcrumbs' => true
])

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="mb-0">{{ $title }}</h3>
            </div>
            @if($breadcrumbs)
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
                    {{ $slot }}
                </ol>
            </div>
            @endif
        </div>
    </div>
</div>
