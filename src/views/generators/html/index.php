@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header clearfix">@if($module)<i class="module-icon fa {{$module->icon}}"></i>@endif  All <?php echo ucfirst($plural); ?>
            
        @if(Entrust::can('create_<?php echo $plural; ?>'))
{{ _d(link_to_route('<?php echo ('backend.' . $plural . '.create'); ?>', '<i class="fa fa-plus"></i> Add new <?php echo $singular; ?>',[],['class'=>'btn btn-success pull-right'])) }}
@endif
        </h1>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
        
        @if ($records->count())
        <table class="table table-striped table-hover table-responsive widget-datatable">
            <thead>
                <tr>
                    <?php foreach($listableFields as $name => $f): ?>
                        <th><?php echo ($f ? $f->label : Str::title($name)); ?></th>
                    <?php endforeach; ?>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($records as $record)
                <tr>
                    <?php foreach($listableFields as $name => $f): ?>
                        <td>{{{ $record-><?php echo $name; ?> }}}</td>
                    <?php endforeach; ?>
                    <td class="al-r">
                        @if(Entrust::can('read_<?php echo $plural; ?>'))
                        {{ link_to_route('<?php echo ('backend.' . $plural . '.show'); ?>', 'Show', array($record->id), array('class' => 'btn btn-sm btn-default')) }}
                        @endif
                        
                        @if(Entrust::can('update_<?php echo $plural; ?>'))
                        {{ link_to_route('<?php echo ('backend.' . $plural . '.edit'); ?>', 'Edit', array($record->id), array('class' => 'btn btn-sm btn-primary')) }}
                        @endif
                        
                        @if(Entrust::can('delete_<?php echo $plural; ?>'))
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('<?php echo ('backend.' . $plural . '.do_delete'); ?>', $record->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        There are no <?php echo $plural; ?>
        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop