@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Permission</h1>

        <p>{{ link_to_route('backend.permissions.index', 'Return to all permissions') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.permissions.do_create'), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name:', 'name', [], 'text', null, []],
                    ['Display_Name:', 'display_name', [], 'text', null, []],
                ])}}
        
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.permissions.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop