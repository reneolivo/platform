@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Create <?php echo ucfirst($singular); ?></h1>

        <p>{{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Return to all <?php echo $plural; ?>') }}</p>

        {{ Form::open(array('method' => 'POST', 'route' => array('<?php echo ('backend.' . $plural . '.do_create'); ?>'), 'role'=>'form')) }}

        <!--FORM_FIELDS-->
        
        <?php
        foreach($generalFields as $name => $f):
            $inputValue = 'null';
            if($f->blueprint_function=='boolean'){
                $inputType = '1';
            }
            //bsField params: labelText, name, inputAttributes, type, value, containerAttributes
            ?>
        {{Form::bsField('<?php echo $f->label ?>', '<?php echo $f->name ?>', [], '<?php echo $f->form_control_type ?>',<?php echo $inputValue ?>, [])}}
        
        <?php endforeach; ?>
        
        <?php
        if($isTranslatable):
        foreach($translatableFields as $name => $f):
            $inputValue = 'null';
            if($f->blueprint_function=='boolean'){
                $inputType = '1';
            }
            //bsField params: labelText, name, inputAttributes, type, value, containerAttributes
            ?>
        {{Form::bsField('<?php echo $f->label ?> ('.Lang::code().')', 'translation[<?php echo $f->name ?>]', [], '<?php echo $f->form_control_type ?>',<?php echo $inputValue ?>, [])}}
        
        <?php endforeach; ?>
        <?php endif; ?>
        
        <!--FORM_FIELDS_END-->

        <div class="form-group">
            {{ Form::button('<i class="fa fa-plus"></i> Create', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'create')) }}
            {{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Cancel', null, array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop