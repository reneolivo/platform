@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Module</h1>

        <p>{{ link_to_route('backend.modules.index', 'Return to all modules') }}</p>

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.modules.do_create'), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name:', 'name', [], 'text', null, []],
                    ['Display_Name:', 'display_name', [], 'text', null, []],
                    ['Icon:', 'icon', [], 'text', null, []],
                    ['Description:', 'description', [], 'text', null, []],
                    ['Is_Pageable:', 'is_pageable', [], 'checkbox', 1, []],
                    ['Is_Translatable:', 'is_translatable', [], 'checkbox', 1, []],
                    ['Is_Imageable:', 'is_imageable', [], 'checkbox', 1, []],
                    ['Is_Active:', 'is_active', [], 'checkbox', 1, []],
                    ['Sorting:', 'sorting', [], 'number', null, []],
                ])}}
        
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.modules.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop