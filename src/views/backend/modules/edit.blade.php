@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Module</h1>

        <p>{{ link_to_route('backend.modules.index', 'Return to all modules') }}</p>

        {{ Form::model($module, array('method' => 'PATCH'
    , 'route' => array('backend.modules.do_edit', $module->id), 'role'=>'form')) }}

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
        
        
        <div class="panel panel-default">
            <div class="panel-heading">
                Related permissions
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php $items_per_row = ceil($permissions->count() / 4); ?>
                    @for($i=1; $i<=4; $i++)
                    <div class="col-md-3">
                        @for($j=1; $j<=$items_per_row; $j++)
                        <?php $perm = $permissions->shift(); ?>
                        @if(is_object($perm))
                        <p><a href="{{route('backend.permissions.edit', [$perm->id])}}">{{$perm->display_name}}</a></p>
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
        
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.modules.index', 'Cancel', array($module->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop