@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('short_message', __('Forbidden'))
@section('message', __($exception->getMessage() ?: 'Forbidden'))
