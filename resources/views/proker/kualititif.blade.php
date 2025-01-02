@extends('layouts_proker.main')

@section('container')
    <p>2. Kualititif</p>


    <form action="/proker/kualititif/{{ $proker->id }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="kualititif" id="" cols="30" rows="10" class="form-control">{{ old('kualititif', $proker) }}</textarea>
    @endsection
</form>
