@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create User</h1>

        <p>{{ link_to_route('backend.users.index', 'Return to all users') }}</p>

        

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.users.store'), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Username *', 'username', [], 'text', null, []],
                    ['Email *', 'email', [], 'text', null, []],
                    ['Password *', 'password', [], 'text', '', []],
                    ['Password confirmation *', 'password_confirmation', [], 'text', '', []],
                    //['Confirmed', 'confirmed', ['checked'], 'checkbox', 1, []],
                    //['Confirmation_Code:', 'confirmation_code', [], 'text', md5(uniqid()), []],
                ])}}
        
        
        <p class="help-block">
            * Required fields
        </p>
        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.users.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop