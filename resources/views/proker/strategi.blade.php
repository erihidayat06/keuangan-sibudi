@extends('layouts_proker.main')

@section('container')
    <form action="/proker/strategi/{{ $proker->id }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="strategi" id="" cols="30" rows="10" class="form-control">{{ old('strategi', $proker) }}</textarea>
    @endsection
</form>
