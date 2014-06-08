@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.modules.index', 'Return to all modules') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $module->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Name:') }}
                    <pre class="well well-sm">{{{ $module->name }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Display_Name:') }}
                    <pre class="well well-sm">{{{ $module->display_name }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Icon:') }}
                    <pre class="well well-sm">{{{ $module->icon }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Description:') }}
                    <pre class="well well-sm">{{{ $module->description }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Pageable:') }}
                    <pre class="well well-sm">{{{ $module->is_pageable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Translatable:') }}
                    <pre class="well well-sm">{{{ $module->is_translatable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Imageable:') }}
                    <pre class="well well-sm">{{{ $module->is_imageable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Active:') }}
                    <pre class="well well-sm">{{{ $module->is_active }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Sorting:') }}
                    <pre class="well well-sm">{{{ $module->sorting }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $module->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $module->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.modules.edit', '<i class="fa fa-pencil"></i> Edit', array($module->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.modules.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop