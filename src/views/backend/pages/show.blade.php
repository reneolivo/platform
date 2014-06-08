@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $record->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Taxonomy:') }}
                    <pre class="well well-sm">{{{ $record->taxonomy }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Controller:') }}
                    <pre class="well well-sm">{{{ $record->controller }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Action:') }}
                    <pre class="well well-sm">{{{ $record->action }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'View:') }}
                    <pre class="well well-sm">{{{ $record->view }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is Https:') }}
                    <pre class="well well-sm">{{{ $record->is_https }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is Indexable:') }}
                    <pre class="well well-sm">{{{ $record->is_indexable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is Deletable:') }}
                    <pre class="well well-sm">{{{ $record->is_deletable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Sorting:') }}
                    <pre class="well well-sm">{{{ $record->sorting }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Status:') }}
                    <pre class="well well-sm">{{{ $record->status }}}</pre>
                </div>
                                                    <div class="form-group">
                    {{ Form::label(null, 'Title:') }}
                    <pre class="well well-sm">{{{ $record->translation()->title }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Content:') }}
                    <pre class="well well-sm">{{{ $record->translation()->content }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Slug:') }}
                    <pre class="well well-sm">{{{ $record->translation()->slug }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Window Title:') }}
                    <pre class="well well-sm">{{{ $record->translation()->window_title }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Meta Description:') }}
                    <pre class="well well-sm">{{{ $record->translation()->meta_description }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Meta Keywords:') }}
                    <pre class="well well-sm">{{{ $record->translation()->meta_keywords }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Canonical Url:') }}
                    <pre class="well well-sm">{{{ $record->translation()->canonical_url }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Redirect Url:') }}
                    <pre class="well well-sm">{{{ $record->translation()->redirect_url }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Redirect Code:') }}
                    <pre class="well well-sm">{{{ $record->translation()->redirect_code }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is translation finished?:') }}
                    <pre class="well well-sm">{{{ $record->translation()->is_translated }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $record->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $record->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.pages.edit', '<i class="fa fa-pencil"></i> Edit', array($record->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.pages.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop