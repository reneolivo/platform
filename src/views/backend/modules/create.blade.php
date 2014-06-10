@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create Module</h1>

        <p>{{ link_to_route('backend.modules.index', 'Return to all modules') }}</p>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        {{ Form::open(array('method' => 'POST', 'route' => array('backend.modules.do_create'), 'role'=>'form')) }}
        <div class="panel panel-default">
            <div class="panel-heading">
                General Info
            </div>
            <div class="panel-body">
                <!-- Form fields here -->
                {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    ['Name:', 'name', [], 'text', null, []],
                    ['Display Name:', 'display_name', [], 'text', null, []],
                    ['Icon:', 'icon', [], 'text', null, []],
                    ['Description:', 'description', [], 'text', null, []],
                    ['Is active', 'is_active', [], 'checkbox', 1, []],
                    ['Sorting:', 'sorting', [], 'number', null, []],
                ])}}
            </div>
            <!--            <div class="panel-footer">
                            Panel Footer
                        </div>-->
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">
                Model definition
            </div>
            <div class="panel-body">
                <?php
                echo Form::bsField('Behaviours', 'behaviours[]', array(
                    'placeholder' => '---',
                    'multiple', 'selected' => null
                        ), 'select2', array(
                    'translatable' => 'Translatable',
                    'pageable' => 'Pageable',
                    'publishable' => 'Publishable',
                    'treeable' => 'Treeable',
                    'imageable' => 'Imageable',
                    'attachablee' => 'Attachablee',
                    'taggable' => 'Taggable',
                    'flaggable' => 'Flaggable',
                    'sortable' => 'Sortable',
                ));
                ?>
                {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
                    //['Behaviours:', 'behaviours', ['placeholder'=>'pageable, imageable, treeable and/or translatable'], 'text', null, []],
                    ['General Fields:', 'general_fields', [
                        'placeholder'=>'columnName:blueprintMethod:formControlType:foreignTable,columnName2:blueprintMethod,columnName3'
                    ], 'text', null, []],
                    ['Translatable Fields:', 'translatable_fields', [
                        'placeholder'=>'columnName:blueprintMethod:formControlType:foreignTable,columnName2:blueprintMethod,columnName3'
                    ], 'text', null, []],
                    ['Listable Fields:', 'listable_fields', ['placeholder'=>'Comma separated list of column names (general or/and translatable)'], 'text', null, []],
                ])}}
            </div>
            <div class="panel-footer">
                Definitions format are the same as in <code>thor:generate</code> command.
                Separate fields with commas, and field options with colons.<br>
                <code>formControlType</code> can be an input compatible with <code>Form::bsField</code> e.g.:
                text, textarea, checkbox, radio, email, colorpicker, datepicker, select, select2, ...
            </div>
        </div>


        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('backend.modules.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop