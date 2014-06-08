@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">All Modules</h1>

        <p>{{ _d(link_to_route('backend.modules.create', '<i class="fa fa-plus"></i> Add new module')) }}</p>

        @if ($modules->count())
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                                            <th>Name</th>
                                            <th>Display_Name</th>
                                            <th>Icon</th>
                                            <th>Description</th>
                                            <th>Is_Pageable</th>
                                            <th>Is_Translatable</th>
                                            <th>Is_Imageable</th>
                                            <th>Is_Active</th>
                                            <th>Sorting</th>
                                        <th>Created at</th>
                    <th>Updated at</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($modules as $module)
                <tr>
                    <td>{{{ $module->id }}}</td>
                                            <td>{{{ $module->name }}}</td>
                                            <td>{{{ $module->display_name }}}</td>
                                            <td><i class="fa {{{ $module->icon }}}"></i></td>
                                            <td>{{{ $module->description }}}</td>
                                            <td>{{{ $module->is_pageable }}}</td>
                                            <td>{{{ $module->is_translatable }}}</td>
                                            <td>{{{ $module->is_imageable }}}</td>
                                            <td>{{{ $module->is_active }}}</td>
                                            <td>{{{ $module->sorting }}}</td>
                                        <td>{{{ $module->created_at }}}</td>
                    <td>{{{ $module->updated_at }}}</td>
                    <td class="al-r">
                        {{ link_to_route('backend.modules.show', 'Show', array($module->id), array('class' => 'btn btn-sm btn-default')) }}
                        {{ link_to_route('backend.modules.edit', 'Edit', array($module->id), array('class' => 'btn btn-sm btn-info')) }}
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('backend.modules.do_delete', $module->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        There are no modules        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop