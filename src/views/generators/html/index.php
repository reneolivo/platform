@extends('<?php echo $viewParent; ?>')
@section('<?php echo $viewSection; ?>')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">All <?php echo ucfirst($plural); ?></h1>

        <p>{{ _d(link_to_route('<?php echo ('admin.' . $plural . '.create'); ?>', '<i class="fa fa-plus"></i> Add new <?php echo $singular; ?>')) }}</p>

        @if ($<?php echo $plural; ?>->count())
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php foreach($fields as $i => $f): $field = $f[1]; ?>
                        <th><?php echo Str::title($field); ?></th>
                    <?php endforeach; ?>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($<?php echo $plural; ?> as $<?php echo $singular; ?>)
                <tr>
                    <td>{{{ $<?php echo $singular; ?>->id }}}</td>
                    <?php foreach($fields as $i => $f): $field = $f[1]; ?>
                        <td>{{{ $<?php echo $singular; ?>-><?php echo $field; ?> }}}</td>
                    <?php endforeach; ?>
                    <td>{{{ $<?php echo $singular; ?>->created_at }}}</td>
                    <td>{{{ $<?php echo $singular; ?>->updated_at }}}</td>
                    <td class="al-r">
                        {{ link_to_route('<?php echo ('admin.' . $plural . '.show'); ?>', 'Show', array($<?php echo $singular; ?>->id), array('class' => 'btn btn-sm btn-default')) }}
                        {{ link_to_route('<?php echo ('admin.' . $plural . '.edit'); ?>', 'Edit', array($<?php echo $singular; ?>->id), array('class' => 'btn btn-sm btn-info')) }}
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('<?php echo ('admin.' . $plural . '.do_delete'); ?>', $<?php echo $singular; ?>->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
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