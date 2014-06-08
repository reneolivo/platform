@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">All Pages</h1>

        <p>{{ _d(link_to_route('backend.pages.create', '<i class="fa fa-plus"></i> Add new page')) }}</p>

        @if ($pages->count())
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                                            <th>Taxonomy</th>
                                            <th>Controller</th>
                                            <th>Action</th>
                                            <th>View</th>
                                            <th>Is_Https</th>
                                            <th>Is_Indexable</th>
                                            <th>Is_Deletable</th>
                                            <th>Sorting</th>
                                            <th>Status</th>
                                        <th>Created at</th>
                    <th>Updated at</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td>{{{ $page->id }}}</td>
                                            <td>{{{ $page->taxonomy }}}</td>
                                            <td>{{{ $page->controller }}}</td>
                                            <td>{{{ $page->action }}}</td>
                                            <td>{{{ $page->view }}}</td>
                                            <td>{{{ $page->is_https }}}</td>
                                            <td>{{{ $page->is_indexable }}}</td>
                                            <td>{{{ $page->is_deletable }}}</td>
                                            <td>{{{ $page->sorting }}}</td>
                                            <td>{{{ $page->status }}}</td>
                                        <td>{{{ $page->created_at }}}</td>
                    <td>{{{ $page->updated_at }}}</td>
                    <td class="al-r">
                        {{ link_to_route('backend.pages.show', 'Show', array($page->id), array('class' => 'btn btn-sm btn-default')) }}
                        {{ link_to_route('backend.pages.edit', 'Edit', array($page->id), array('class' => 'btn btn-sm btn-info')) }}
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('backend.pages.do_delete', $page->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        There are no pages        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop