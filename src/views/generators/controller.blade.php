<?php echo '<?php'; ?>

namespace {{$controllerNamespace}};

use View,
    Redirect,
    Form, Input;
/*
|--------------------------------------------------------------------------
| Backend controller for {{$modelFullName}}
|--------------------------------------------------------------------------
|
| This is a default Thor CMS backend controller template for resource management.
| Feel free to change it to your needs.
|
*/
class {{$controllerShortName}} extends \Thor\Backend\Controller {

    /**
     * Model repository
     *
     * @var {{$modelFullName}}
     */
    protected $model;

    public function __construct({{$modelFullName}} $model) {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $items = $this->model->all();

        return View::make('thor::backend.{{$plural}}.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('thor::backend.{{$plural}}.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Input::all();
        @if($isTranslatable)
        $transl_model = new {{$modelFullName}}Text();
        $transl_input = array_except(Input::get('translation'), 'id');
        $transl_errors = array();
        @endif

        if ($this->model->validate($input)) {
            $model = $this->model->create($input);
            @if($isTranslatable)if ($transl_model->validate($transl_input)) {
                $transl_model = $transl_model->create(array_merge(array('language_id' => \Lang::id(), '{{$singular}}_id' => $model->id), $transl_input));
            @endif
                return Redirect::route('backend.{{$plural}}.index');
            @if($isTranslatable)
            }else{
                $transl_errors = $transl_model->errors();
            }
            @endif
        }

        return Redirect::route('backend.{{$plural}}.create')
                        ->withInput()
                        ->withErrors($this->model->errors())
                        @if($isTranslatable)->withErrors($transl_errors)@endif
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  {{$modelFullName}}  $model 
     * @return Response
     */
    public function show({{$modelFullName}} $model) {

        return View::make('thor::backend.{{$plural}}.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{$modelFullName}}  $model 
     * @return Response
     */
    public function edit({{$modelFullName}} $model) {

        if (is_null($model)) {
            return Redirect::route('backend.{{$plural}}.index');
        }

        return View::make('thor::backend.{{$plural}}.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  {{$modelFullName}}  $model 
     * @return Response
     */
    public function do_edit({{$modelFullName}} $model) {
        $input = array_merge(array( // for unchecked checkboxes:
            @foreach($generalFields as $name => $def)
            @if($def->blueprint_function=='boolean')
            '{{$name}}'=>false,
            @endif
            @endforeach
        ), Input::all());
        
        @if($isTranslatable)
        $transl_input = array_merge(array( // for unchecked checkboxes:
            @foreach($translatableFields as $name => $def)
            @if($def->blueprint_function=='boolean')
            '{{$name}}'=>false,
            @endif
            @endforeach
        ), array_except(Input::get('translation'), 'id'));
        $transl_model = new {{$modelFullName}}Text();
        $transl_errors = array();
        @endif

        if ($model->validate($input)) {
            $model->update($input);
            @if($isTranslatable)if ($transl_model->validate($transl_input)) {
                $transl_model = $transl_model->create(array_merge(array('language_id' => \Lang::id(), '{{$singular}}_id' => $model->id), $transl_input));
                
                // Save translation
                if (Input::get('translation.id')) {
                    $transl_model = {{$modelFullName}}Text::find(Input::get('translation.id'));
                    $transl->update($transl_input);
                } else {
                    $transl_model = $transl_model->create(array_merge(array('language_id' => \Lang::id(), '{{$singular}}_id' => $model->id), $transl_input));
                }
            @endif
            return Redirect::route('backend.{{$plural}}.edit', $model->id);
            @if($isTranslatable)
            }else{
                $transl_errors = $transl_model->errors();
            }
            @endif
        }
        
        return Redirect::route('backend.{{$plural}}.edit', $model->id)
                        ->withInput()
                        ->withErrors($model->errors())
                        @if($isTranslatable)->withErrors($transl_errors)@endif
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{$modelFullName}}  $model 
     * @return Response
     */
    public function do_delete({{$modelFullName}} $model) {
        $model->delete();

        return Redirect::route('backend.{{$plural}}.index');
    }

}
