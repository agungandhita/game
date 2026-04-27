@extends('admin.layouts.main')

@section('title', $user->name)

@section('container')
{{-- Redirect to scores page --}}
@php
    header('Location: ' . route('admin.users.scores', $user->id));
    exit;
@endphp
@endsection
