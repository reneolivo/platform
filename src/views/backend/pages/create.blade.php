@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Page</h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.pages.do_create'), 'role'=>'form')) }}

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
                    ['Title ('.Lang::code().'):', 'translation[title]', [], 'text', null, []],
                    ['Content ('.Lang::code().'):', 'translation[content]', [], 'text', null, []],
                    ['Slug ('.Lang::code().'):', 'translation[slug]', [], 'text', null, []],
                    ['Window_Title ('.Lang::code().'):', 'translation[window_title]', [], 'text', null, []],
                    ['Meta_Description ('.Lang::code().'):', 'translation[meta_description]', [], 'text', null, []],
                    ['Meta_Keywords ('.Lang::code().'):', 'translation[meta_keywords]', [], 'text', null, []],
                    ['Canonical_Url ('.Lang::code().'):', 'translation[canonical_url]', [], 'text', null, []],
                    ['Redirect_Url ('.Lang::code().'):', 'translation[redirect_url]', [], 'text', null, []],
                    ['Redirect_Code ('.Lang::code().'):', 'translation[redirect_code]', [], 'text', null, []],
                    ['Translation_Status ('.Lang::code().'):', 'translation[translation_status]', [], 'text', null, []],
                ])}}
        
        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.pages.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop