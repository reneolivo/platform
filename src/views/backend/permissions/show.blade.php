@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.permissions.index', 'Return to all permissions') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $permission->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Name:') }}
                    <pre class="well well-sm">{{{ $permission->name }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Display_Name:') }}
                    <pre class="well well-sm">{{{ $permission->display_name }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $permission->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $permission->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.permissions.edit', '<i class="fa fa-pencil"></i> Edit', array($permission->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.permissions.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop