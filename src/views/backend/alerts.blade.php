<div class="backend-alerts alerts auto-hide">
    @if (Session::get('message'))
    <div class="alert alert-default fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('message')}}
    </div>
    @endif

    @if ($errors->any())
    {{ implode('', $errors->all('<div class="alert alert-danger fade in">'
                .'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
                .':message</div>')) }}
    @endif

    @if (Session::get('warning_message'))
    <div class="alert alert-warning fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('warning_message')}}
    </div>
    @endif

    @if (Session::get('info_message'))
    <div class="alert alert-info fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('info_message')}}
    </div>
    @endif

    @if (Session::get('success_message'))
    <div class="alert alert-success fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('success_message')}}
    </div>
    @endif
</div>