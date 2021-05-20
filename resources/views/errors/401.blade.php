@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('short_message', __('Unauthorized'))
@section('message', __('Access is denied.'))
