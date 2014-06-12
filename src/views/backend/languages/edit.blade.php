@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Language</h1>

        <p>{{ link_to_route('backend.languages.index', 'Return to all languages') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::model($language, array('method' => 'PATCH'
    , 'route' => array('backend.languages.update', $language->id), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name:', 'name', [], 'text', null, []],
                    ['Code:', 'code', [], 'text', null, []],
                    ['Locale:', 'locale', [], 'text', null, []],
                    ['Is_Active:', 'is_active', [], 'checkbox', 1, []],
                    ['Sorting:', 'sorting', [], 'number', null, []],
        ])}}
        
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.languages.index', 'Cancel', array($language->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop