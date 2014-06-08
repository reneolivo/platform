@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edit <?php echo ucfirst($singular); ?></h1>

        <p>{{ link_to_route('<?php echo ('admin.' . $plural . '.index'); ?>', 'Return to all <?php echo $plural; ?>') }}</p>

        {{ Form::model($<?php echo $singular; ?>, array('method' => 'PATCH'
    , 'route' => array('<?php echo ('admin.' . $plural . '.do_edit'); ?>', $<?php echo $singular; ?>->id), 'role'=>'form')) }}
<?php if($isTranslatable): ?>
<?php echo "<?php\n"; ?>
    $transl = $<?php echo $singular; ?>->translation();
<?php echo "?>"; ?>
{{ Form::hidden('translation[id]', $transl->id) }}
<?php endif; ?>

        <!-- Form fields here -->
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
        <?php
        foreach($fields as $i => $f):
            $field = $f[1];
            $fieldType = ($f[0] == 'boolean') ? 'checkbox' : (($f[0] == 'integer') ? 'number' : 'text');
            $fieldValue = ($f[0] == 'boolean') ? '1' : 'null';
            ?>
            ['<?php echo Str::title($field); ?>', '<?php echo $field; ?>', [], '<?php echo $fieldType; ?>', <?php echo $fieldValue; ?>, []],
        <?php endforeach; ?>
])}}
        
        <?php if($isTranslatable): ?>
        {{Form::bsFields([
    //label, name, attributes, type, value, containerAttributes
        <?php
        foreach($transFields as $i => $f):
            $field = $f[1];
            $fieldType = ($f[0] == 'boolean') ? 'checkbox' : (($f[0] == 'integer') ? 'number' : 'text');
            $fieldValue = ($f[0] == 'boolean') ? '1' : ('$transl->'.$field);
            ?>
            ['<?php echo Str::title($field); ?> ('.Lang::code().')', 'translation[<?php echo $field; ?>]', [], '<?php echo $fieldType; ?>', <?php echo $fieldValue; ?>, []],
        <?php endforeach; ?>
        ])}}
        <?php endif; ?>

        <div class="form-group">
            {{ Form::button('<i class="fa fa-floppy-o"></i> Save', array('class' => 'btn btn-primary', 'type'=>'submit', 'value'=>'update')) }}
            {{ link_to_route('<?php echo ('admin.' . $plural . '.index'); ?>', 'Cancel', array($<?php echo $singular; ?>->id), array('class' => 'btn btn-default')) }}
        </div>

        {{ Form::close() }}

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop