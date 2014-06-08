@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Page</h1>

        <p>{{ link_to_route('backend.pages.index', 'Return to all pages') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.pages.do_create'), 'role'=>'form')) }}

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
        
                
                {{Form::bsField('Title ('.Lang::code().')', 'translation[title]', [], 'text',null, [])}}
        
                {{Form::bsField('Content ('.Lang::code().')', 'translation[content]', [], 'html',null, [])}}
        
                {{Form::bsField('Slug ('.Lang::code().')', 'translation[slug]', [], 'text',null, [])}}
        
                {{Form::bsField('Window Title ('.Lang::code().')', 'translation[window_title]', [], 'text',null, [])}}
        
                {{Form::bsField('Meta Description ('.Lang::code().')', 'translation[meta_description]', [], 'text',null, [])}}
        
                {{Form::bsField('Meta Keywords ('.Lang::code().')', 'translation[meta_keywords]', [], 'text',null, [])}}
        
                {{Form::bsField('Canonical Url ('.Lang::code().')', 'translation[canonical_url]', [], 'text',null, [])}}
        
                {{Form::bsField('Redirect Url ('.Lang::code().')', 'translation[redirect_url]', [], 'text',null, [])}}
        
                {{Form::bsField('Redirect Code ('.Lang::code().')', 'translation[redirect_code]', [], 'text',null, [])}}
        
                {{Form::bsField('Is translation finished? ('.Lang::code().')', 'translation[is_translated]', [], 'checkbox',null, [])}}
        
                        
        <!--FORM_FIELDS_END-->

        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.pages.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop