@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Show <?php $model_name; ?></h1>

        <p>{{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Return to all <?php echo $plural; ?>') }}</p>

        <section class="resource-show">
            <div class="form-group">
                {{ Form::label(null, 'ID:') }}
                <pre class="well well-sm">{{{ $<?php echo $singular; ?>->id }}}</pre>
            </div>
            <?php foreach($fields as $i => $f): $field = $f[1]; ?>
                <div class="form-group">
                    {{ Form::label(null, '<?php echo Str::title($field); ?>:') }}
                    <pre class="well well-sm">{{{ $<?php echo $singular; ?>-><?php echo $field; ?> }}}</pre>
                </div>
            <?php endforeach; ?>
            <?php if($isTranslatable): ?>
            <?php foreach($transFields as $i => $f): $field = $f[1]; ?>
                <div class="form-group">
                    {{ Form::label(null, '<?php echo Str::title($field); ?>:') }}
                    <pre class="well well-sm">{{{ $<?php echo $singular; ?>->translation()-><?php echo $field; ?> }}}</pre>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-group">
                {{ Form::label(null, 'Created at:') }}
                <pre class="well well-sm">{{{ $<?php echo $singular; ?>->created_at }}}</pre>
            </div>
            <div class="form-group">
                {{ Form::label(null, 'Updated at:') }}
                <pre class="well well-sm">{{{ $<?php echo $singular; ?>->updated_at }}}</pre>
            </div>

            <div class="form-group">
                {{ _d(link_to_route('<?php echo ('backend.' . $plural . '.edit'); ?>', '<i class="fa fa-pencil"></i> Edit', array($<?php echo $singular; ?>->id), array('class' => 'btn btn-info'))) }}
                {{ link_to_route('<?php echo ('backend.' . $plural . '.index'); ?>', 'Cancel', null, array('class' => 'btn btn-default')) }}
            </div>
        </section>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop