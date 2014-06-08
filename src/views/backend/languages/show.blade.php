@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.languages.index', 'Return to all languages') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $language->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Name:') }}
                    <pre class="well well-sm">{{{ $language->name }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Code:') }}
                    <pre class="well well-sm">{{{ $language->code }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Locale:') }}
                    <pre class="well well-sm">{{{ $language->locale }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Active:') }}
                    <pre class="well well-sm">{{{ $language->is_active }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Sorting:') }}
                    <pre class="well well-sm">{{{ $language->sorting }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $language->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $language->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.languages.edit', '<i class="fa fa-pencil"></i> Edit', array($language->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.languages.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop