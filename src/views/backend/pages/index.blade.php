@extends('thor::backend.layout')
@section('main')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header clearfix">All Pages            
            @if(Entrust::can('create_pages'))
            {{ _d(link_to_route('backend.pages.create', '<i class="fa fa-plus"></i> Add new page',[],['class'=>'btn btn-success pull-right'])) }}
            @endif
        </h1>

        @if ($errors->any())

        {{ implode('', $errors->all('<p class="alert alert-danger">:message</p>')) }}

        @endif

        @if ($items->count())
        <table class="table table-striped table-hover table-responsive widget-datatable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Controller</th>
                    <th>Action</th>
                    <th>View</th>
                    <th>Status</th>
                    <th class="al-r">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{{ $item->title }}}</td>
                    <td>{{{ $item->slug }}}</td>
                    <td>{{{ $item->controller }}}</td>
                    <td>{{{ $item->action }}}</td>
                    <td>{{{ $item->view }}}</td>
                    <td>{{{ $item->status }}}</td>
                    <td class="al-r">
                        @if(Entrust::can('read_pages'))
                        {{ _d(link_to($item->url, 'Show <i class="fa fa-share"></i> ', 
                                    array('class' => 'btn btn-sm btn-default', 'target'=>'_blank'))) }}
                        @endif

                        @if(Entrust::can('update_pages'))
                        {{ link_to_route('backend.pages.edit', 'Edit', array($item->id), array('class' => 'btn btn-sm btn-primary')) }}
                        @endif

                        @if(Entrust::can('delete_pages'))
                        {{ Form::open(array('method' => 'DELETE', 'class' => 'inl-bl', 'route' => array('backend.pages.do_delete', $item->id))) }}
                        {{ Form::submit('Delete', array('class' => 'btn btn-sm btn-danger btn-destroy')) }}
                        {{ Form::close() }}
                        @endif
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