@foreach ($dispositivosActivos as $dispositivo)
  <option value="{{ $dispositivo->nombre }}"></option>
@endforeach
