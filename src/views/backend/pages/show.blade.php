@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show </h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $page->id }}}</pre>
            </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Taxonomy:') }}
                    <pre class="well well-sm">{{{ $page->taxonomy }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Controller:') }}
                    <pre class="well well-sm">{{{ $page->controller }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Action:') }}
                    <pre class="well well-sm">{{{ $page->action }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'View:') }}
                    <pre class="well well-sm">{{{ $page->view }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Https:') }}
                    <pre class="well well-sm">{{{ $page->is_https }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Indexable:') }}
                    <pre class="well well-sm">{{{ $page->is_indexable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Is_Deletable:') }}
                    <pre class="well well-sm">{{{ $page->is_deletable }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Sorting:') }}
                    <pre class="well well-sm">{{{ $page->sorting }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Status:') }}
                    <pre class="well well-sm">{{{ $page->status }}}</pre>
                </div>
                                                    <div class="form-group">
                    {{ Form::label(null, 'Title:') }}
                    <pre class="well well-sm">{{{ $page->translation()->title }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Content:') }}
                    <pre class="well well-sm">{{{ $page->translation()->content }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Slug:') }}
                    <pre class="well well-sm">{{{ $page->translation()->slug }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Window_Title:') }}
                    <pre class="well well-sm">{{{ $page->translation()->window_title }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Meta_Description:') }}
                    <pre class="well well-sm">{{{ $page->translation()->meta_description }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Meta_Keywords:') }}
                    <pre class="well well-sm">{{{ $page->translation()->meta_keywords }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Canonical_Url:') }}
                    <pre class="well well-sm">{{{ $page->translation()->canonical_url }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Redirect_Url:') }}
                    <pre class="well well-sm">{{{ $page->translation()->redirect_url }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Redirect_Code:') }}
                    <pre class="well well-sm">{{{ $page->translation()->redirect_code }}}</pre>
                </div>
                            <div class="form-group">
                    {{ Form::label(null, 'Translation_Status:') }}
                    <pre class="well well-sm">{{{ $page->translation()->translation_status }}}</pre>
                </div>
                                    <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $page->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $page->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('backend.pages.edit', '<i class="fa fa-pencil"></i> Edit', array($page->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('backend.pages.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop