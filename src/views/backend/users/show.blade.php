@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.users.index', 'Return to all users') }}</p>

        

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $user->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Username:') }}
                    <pre class="well well-sm">{{{ $user->username }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Email:') }}
                    <pre class="well well-sm">{{{ $user->email }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Password:') }}
                    <pre class="well well-sm">{{{ $user->password }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Confirmed:') }}
                    <pre class="well well-sm">{{{ $user->confirmed }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Confirmation_Code:') }}
                    <pre class="well well-sm">{{{ $user->confirmation_code }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $user->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $user->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.users.edit', '<i class="fa fa-pencil"></i> Edit', array($user->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.users.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop