@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.roles.index', 'Return to all roles') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $role->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Name:') }}
                    <pre class="well well-sm">{{{ $role->name }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $role->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $role->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.roles.edit', '<i class="fa fa-pencil"></i> Edit', array($role->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.roles.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop