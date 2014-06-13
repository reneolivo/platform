@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Role</h1>

        <p>{{ link_to_route('backend.roles.index', 'Return to all roles') }}</p>

        

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.roles.store'), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name *', 'name', [], 'text', null, []],
                    ['Display name', 'display_name', [], 'text', null, []],
                    ['Description', 'description', [], 'text', null, []],
                ])}}
        
        
        <p class="help-block">
            * Required fields
        </p>
        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.roles.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop