@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit <?php echo ucfirst($singular); ?></h1>

        <p>{{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Return to all <?php echo $plural; ?>') }}</p>

        {{ Form::model($<?php echo $singular; ?>, array('method' => 'PATCH'
    , 'route' => array('<?php echo ('backend.' . $plural . '.do_edit'); ?>', $<?php echo $singular; ?>->id), 'role'=>'form')) }}
<?php if($isTranslatable): ?>
<?php echo "<?php\n"; ?>
    $transl = $<?php echo $singular; ?>->translation();
<?php echo "?>"; ?>
{{ Form::hidden('translation[id]', $transl->id) }}
<?php endif; ?>

        <!--FORM_FIELDS-->
        
        <?php
        foreach($generalFields as $name => $f):
            $inputValue = 'null';
            if($f->data_type=='boolean'){
                $inputType = '1';
            }
            //bsField params: labelText, name, inputAttributes, type, value, containerAttributes
            ?>
        {{Form::bsField(array('<?php echo $f->label ?>', '<?php echo $f->name ?>', [], '<?php echo $f->control_type ?>',<?php echo $inputValue ?>, []))}}
        
        <?php endforeach; ?>
       
        
        <?php
        if($isTranslatable):
        foreach($translatableFields as $name => $f):
            $inputValue = '$transl->'.$field;
            if($f->data_type=='boolean'){
                $inputType = '1';
            }
            //bsField params: labelText, name, inputAttributes, type, value, containerAttributes
            ?>
        {{Form::bsField(array('<?php echo $f->label ?> ('.Lang::code().')', 'translation[<?php echo $f->name ?>]', [], '<?php echo $f->control_type ?>',<?php echo $inputValue ?>, []))}}
        
        <?php endforeach; ?>
        <?php endif; ?>
        
        <!--FORM_FIELDS_END-->

        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Cancel', array($<?php echo $singular; ?>->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop