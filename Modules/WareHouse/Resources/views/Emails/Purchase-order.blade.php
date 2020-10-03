@extends('notifications::Emails.layout')

@section('email-content')

    <p>This Email attached your requested {{ $render_data['title'] }}.</p>

@endsection
