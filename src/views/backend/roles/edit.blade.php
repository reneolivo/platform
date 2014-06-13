@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Role</h1>

        <p>{{ link_to_route('backend.roles.index', 'Return to all roles') }}</p>

        

        {{ Form::model($role, array('method' => 'PATCH'
    , 'route' => array('backend.roles.update', $role->id), 'role'=>'form')) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name *', 'name', [], 'text', null, []],
                    ['Display name', 'display_name', [], 'text', null, []],
                    ['Description', 'description', [], 'text', null, []],
        ])}}
        
        
        <div class="panel panel-default panel-checkboxes">
            <div class="panel-heading">
                Assigned permissions
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php $items_per_row = ceil($permissions->count() / 4); ?>
                    @for($i=1; $i<=4; $i++)
                    <div class="col-md-3">
                        @for($j=1; $j<=$items_per_row; $j++)
                        <?php $perm = $permissions->shift(); ?>
                        @if(is_object($perm))
                        <?php
                            $attrs = array();
                            $containerAttrs = array();
                            if(!Sentinel::can('update_permissions')){
                                $containerAttrs['class']='form-group text-muted';
                            }
                            if(in_array($perm->id, $role_permissions)){
                                $attrs[]='checked';
                            }
                        ?>
                    {{Form::bsField($perm->display_name, 'perms[]', $attrs, 'checkbox', $perm->id, $containerAttrs)}}
                        @endif
                        @endfor
                    </div>
                    @endfor
                </div>
            </div>
<!--            <div class="panel-footer">
                Panel Footer
            </div>-->
        </div>
        
        
        <div class="panel panel-default panel-checkboxes">
            <div class="panel-heading">
                Users with this role
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php $items_per_row = ceil($users->count() / 4); ?>
                    @for($i=1; $i<=4; $i++)
                    <div class="col-md-3">
                        @for($j=1; $j<=$items_per_row; $j++)
                        <?php $user = $users->shift(); ?>
                        @if(is_object($user))
                        <?php
                            $attrs = array();
                            $containerAttrs = array();
                            if(!Sentinel::can('update_roles')){
                                //$attrs['disabled']='disabled';
                                $containerAttrs['class']='form-group text-muted';
                            }
                            if(in_array($user->id, $role_users)){
                                $attrs[]='checked';
                            }
                        ?>
                    {{Form::bsField($user->username, 'users[]', $attrs, 'checkbox', $user->id, $containerAttrs)}}
                        @endif
                        @endfor
                    </div>
                    @endfor
                </div>
            </div>
<!--            <div class="panel-footer">
                Panel Footer
            </div>-->
        </div>
        
        
        <p class="help-block">
            * Required fields
        </p>
        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.roles.index', 'Cancel', array($role->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop