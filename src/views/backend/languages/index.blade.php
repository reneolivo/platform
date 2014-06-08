@extends('admin::layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">All Languages</h1>

        <p>{{ _d(link_to_route('admin.languages.create', '<i class="fa fa-plus"></i> Add new language')) }}</p>

        @if ($languages->count())
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Locale</th>
                                            <th>Is_Active</th>
                                            <th>Sorting</th>
                                        <th>Created at</th>
                    <th>Updated at</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($languages as $language)
                <tr>
                    <td>{{{ $language->id }}}</td>
                                            <td>{{{ $language->name }}}</td>
                                            <td>{{{ $language->code }}}</td>
                                            <td>{{{ $language->locale }}}</td>
                                            <td>{{{ $language->is_active }}}</td>
                                            <td>{{{ $language->sorting }}}</td>
                                        <td>{{{ $language->created_at }}}</td>
                    <td>{{{ $language->updated_at }}}</td>
                    <td class="al-r">
                        {{ link_to_route('admin.languages.show', 'Show', array($language->id), array('class' => 'btn btn-sm btn-default')) }}
                        {{ link_to_route('admin.languages.edit', 'Edit', array($language->id), array('class' => 'btn btn-sm btn-info')) }}
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('admin.languages.do_delete', $language->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        There are no languages        @endif

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop