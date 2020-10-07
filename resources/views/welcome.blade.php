@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-4">
            <wormhole-report-form :system-options="{{$systemOptions}}" :signature-list='@json($signatureList)'
                report-url="{{route('report')}}">
            </wormhole-report-form>
        </div>
    </div>
@endsection
