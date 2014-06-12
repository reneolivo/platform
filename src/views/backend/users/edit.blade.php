@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit User</h1>

        <p>{{ link_to_route('backend.users.index', 'Return to all users') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::model($user, array('method' => 'PATCH'
    , 'route' => array('backend.users.update', $user->id), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Username:', 'username', [], 'text', null, []],
                    ['Email:', 'email', [], 'text', null, []],
                    ['New password:', 'password', [], 'text', '', []],
                    ['New password confirmation:', 'password_confirmation', [], 'text', '', []],
                    ['Confirmed', 'confirmed', [], 'checkbox', 1, []],
                    //['Confirmation_Code:', 'confirmation_code', ['readonly', 'disabled'], 'text', null, []],
        ])}}
        
        
        <div class="panel panel-default panel-checkboxes">
            <div class="panel-heading">
                Roles for this user
            </div>
            <div class="panel-body">
                    @foreach($roles as $i => $role)
                        <?php
                            $attrs = array();
                            $containerAttrs = array();
                            //$name
                            if(((in_array($role->name, array('developer'))) and ($user->username=='developer') )
                                    or (!Sentinel::can('update_roles'))){
                                //$attrs['disabled']='disabled';
                                $containerAttrs['class']='form-group text-muted';
                            }
                            if(in_array($role->id, $user_roles)){
                                $attrs[]='checked';
                            }
                        ?>
                    {{Form::bsField($role->name, 'roles[]', $attrs, 'checkbox', $role->id, $containerAttrs)}}
                    @endforeach
            </div>
<!--            <div class="panel-footer">
                Panel Footer
            </div>-->
        </div>


        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.users.index', 'Cancel', array($user->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop