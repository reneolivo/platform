@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Page</h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        {{ Form::model($page, array('method' => 'PATCH'
    , 'route' => array('backend.pages.do_edit', $page->id), 'role'=>'form')) }}
<?php
    $transl = $page->translation();
?>{{ Form::hidden('translation[id]', $transl->id) }}

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Taxonomy:', 'taxonomy', [], 'text', null, []],
                    ['Controller:', 'controller', [], 'text', null, []],
                    ['Action:', 'action', [], 'text', null, []],
                    ['View:', 'view', [], 'text', null, []],
                    ['Is_Https:', 'is_https', [], 'checkbox', 1, []],
                    ['Is_Indexable:', 'is_indexable', [], 'checkbox', 1, []],
                    ['Is_Deletable:', 'is_deletable', [], 'checkbox', 1, []],
                    ['Sorting:', 'sorting', [], 'number', null, []],
                    ['Status:', 'status', [], 'text', null, []],
        ])}}
        
                {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Title ('.Lang::code().'):', 'translation[title]', [], 'text', $transl->title, []],
                    ['Content ('.Lang::code().'):', 'translation[content]', [], 'text', $transl->content, []],
                    ['Slug ('.Lang::code().'):', 'translation[slug]', [], 'text', $transl->slug, []],
                    ['Window_Title ('.Lang::code().'):', 'translation[window_title]', [], 'text', $transl->window_title, []],
                    ['Meta_Description ('.Lang::code().'):', 'translation[meta_description]', [], 'text', $transl->meta_description, []],
                    ['Meta_Keywords ('.Lang::code().'):', 'translation[meta_keywords]', [], 'text', $transl->meta_keywords, []],
                    ['Canonical_Url ('.Lang::code().'):', 'translation[canonical_url]', [], 'text', $transl->canonical_url, []],
                    ['Redirect_Url ('.Lang::code().'):', 'translation[redirect_url]', [], 'text', $transl->redirect_url, []],
                    ['Redirect_Code ('.Lang::code().'):', 'translation[redirect_code]', [], 'text', $transl->redirect_code, []],
                    ['Translation_Status ('.Lang::code().'):', 'translation[translation_status]', [], 'text', $transl->translation_status, []],
                ])}}
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.pages.index', 'Cancel', array($page->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop