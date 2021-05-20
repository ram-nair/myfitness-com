@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('short_message', __('Sorry!'))
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))
