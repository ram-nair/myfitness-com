@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('short_message', __('OOPS!'))
@section('message', __('Server Error'))
