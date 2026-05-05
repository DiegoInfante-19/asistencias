<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="fecha" class="form-label">{{ __('Fecha') }}</label>
            <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $asistencia?->fecha) }}" id="fecha" placeholder="Fecha">
            {!! $errors->first('fecha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="miembro_id" class="form-label">{{ __('Miembro') }}</label>

            <select name="miembro_id" id="miembro_id" class="form-control @error('miembro_id') is-invalid @enderror">
                <option value="">-- Seleccione un Miembro --</option>
                @foreach($miembros as $id => $nombre)
                <option value="{{ $id }}" {{ old('miembro_id', $asistencia?->miembro_id) == $id ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
                @endforeach
            </select>

            {!! $errors->first('miembro_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>