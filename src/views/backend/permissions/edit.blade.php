@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Permission</h1>

        <p>{{ link_to_route('backend.permissions.index', 'Return to all permissions') }}</p>

        {{ Form::model($permission, array('method' => 'PATCH'
    , 'route' => array('backend.permissions.do_edit', $permission->id), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name:', 'name', [], 'text', null, []],
                    ['Display_Name:', 'display_name', [], 'text', null, []],
        ])}}
        
        
        <div class="panel panel-default panel-checkboxes">
            <div class="panel-heading">
                Roles using this permission
            </div>
            <div class="panel-body">
                    @foreach($roles as $i => $role)
                        <?php
                            $attrs = array();
                            $containerAttrs = array();
                            if(((in_array($role->name, array('developer', 'administrator'))) and ($permission->name==Backend::ACCESS_PERMISSION_NAME) )
                                    or (!Entrust::can('update_roles'))){
                                //$attrs['disabled']='disabled';
                                $containerAttrs['class']='form-group text-muted';
                            }
                            if(in_array($role->id, $permission_roles)){
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
            {{ link_to_route('backend.permissions.index', 'Cancel', array($permission->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop