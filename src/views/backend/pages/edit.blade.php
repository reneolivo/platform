@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit Page</h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::model($record, array('method' => 'PATCH'
    , 'route' => array('backend.pages.do_edit', $record->id), 'role'=>'form')) }}
<?php
    $transl = $record->translation();
?>{{ Form::hidden('translation[id]', $transl->id) }}

        <!--FORM_FIELDS-->
        
                {{Form::bsField('Taxonomy', 'taxonomy', [], 'text',null, [])}}
        
                {{Form::bsField('Controller', 'controller', [], 'text',null, [])}}
        
                {{Form::bsField('Action', 'action', [], 'text',null, [])}}
        
                {{Form::bsField('View', 'view', [], 'text',null, [])}}
        
                {{Form::bsField('Is Https', 'is_https', [], 'checkbox',null, [])}}
        
                {{Form::bsField('Is Indexable', 'is_indexable', [], 'checkbox',null, [])}}
        
                {{Form::bsField('Is Deletable', 'is_deletable', [], 'checkbox',null, [])}}
        
                {{Form::bsField('Sorting', 'sorting', [], 'number',null, [])}}
        
                {{Form::bsField('Status', 'status', [], 'text',null, [])}}
        
               
        
                {{Form::bsField('Title ('.Lang::code().')', 'translation[title]', [], 'text',$transl->title, [])}}
        
                {{Form::bsField('Content ('.Lang::code().')', 'translation[content]', [], 'html',$transl->content, [])}}
        
                {{Form::bsField('Slug ('.Lang::code().')', 'translation[slug]', [], 'text',$transl->slug, [])}}
        
                {{Form::bsField('Window Title ('.Lang::code().')', 'translation[window_title]', [], 'text',$transl->window_title, [])}}
        
                {{Form::bsField('Meta Description ('.Lang::code().')', 'translation[meta_description]', [], 'text',$transl->meta_description, [])}}
        
                {{Form::bsField('Meta Keywords ('.Lang::code().')', 'translation[meta_keywords]', [], 'text',$transl->meta_keywords, [])}}
        
                {{Form::bsField('Canonical Url ('.Lang::code().')', 'translation[canonical_url]', [], 'text',$transl->canonical_url, [])}}
        
                {{Form::bsField('Redirect Url ('.Lang::code().')', 'translation[redirect_url]', [], 'text',$transl->redirect_url, [])}}
        
                {{Form::bsField('Redirect Code ('.Lang::code().')', 'translation[redirect_code]', [], 'text',$transl->redirect_code, [])}}
        
                {{Form::bsField('Is translation finished? ('.Lang::code().')', 'translation[is_translated]', [], 'checkbox',$transl->is_translated, [])}}
        
                        
        <!--FORM_FIELDS_END-->

        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('backend.pages.index', 'Cancel', array($record->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop