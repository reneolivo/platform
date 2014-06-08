@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">All Permissions</h1>

        <p>{{ _d(link_to_route('backend.permissions.create', '<i class="fa fa-plus"></i> Add new permission')) }}</p>

        @if ($permissions->count())
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                                            <th>Name</th>
                                            <th>Display_Name</th>
                                        <th>Created at</th>
                    <th>Updated at</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($permissions as $permission)
                <tr>
                    <td>{{{ $permission->id }}}</td>
                                            <td>{{{ $permission->name }}}</td>
                                            <td>{{{ $permission->display_name }}}</td>
                                        <td>{{{ $permission->created_at }}}</td>
                    <td>{{{ $permission->updated_at }}}</td>
                    <td class="al-r">
                        {{ link_to_route('backend.permissions.show', 'Show', array($permission->id), array('class' => 'btn btn-sm btn-default')) }}
                        {{ link_to_route('backend.permissions.edit', 'Edit', array($permission->id), array('class' => 'btn btn-sm btn-info')) }}
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('backend.permissions.do_delete', $permission->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        There are no permissions        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop