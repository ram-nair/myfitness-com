@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('short_message', __('OOPS!'))
@section('message', __('Too Many Requests'))
