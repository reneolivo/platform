<?php echo '<?php'; ?>

namespace {{$controllerNamespace}};

use View,
    Redirect,
    Input;
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
    protected $record;

    public function __construct({{$modelFullName}} $record) {
        $this->record = $record;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $records = $this->record->all();
        $module = {{$modelFullName}}::relatedModule();

        return View::make('thor::backend.{{$plural}}.index', compact('records', 'module'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $module = {{$modelFullName}}::relatedModule();
        return View::make('thor::backend.{{$plural}}.create', compact('module'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Input::all();
        @if($isTranslatable)
        $transl_record = new {{$modelFullName}}Text();
        $transl_input = array_except(Input::get('translation'), 'id');
        $transl_errors = array();
        @endif

        if ($this->record->validate($input, null, $this->record->getValidationRules())) {
            $record = $this->record->create($input);
            @if($isTranslatable)if ($transl_record->validate($transl_input, null, $transl_record->getValidationRules())) {
                $transl_record = $transl_record->create(array_merge(array('language_id' => \Lang::id(), '{{$singular}}_id' => $record->id), $transl_input));
            @endif
                return Redirect::route('backend.{{$plural}}.edit', array($record->id))
                    ->with('success_message', '{{ucfirst($singular)}} created successfully.');
            @if($isTranslatable)
            }else{
                $transl_errors = $transl_record->errors();
            }
            @endif
        }

        return Redirect::route('backend.{{$plural}}.create')
                        ->withInput()
                        ->withErrors($this->record->errors())
                        @if($isTranslatable)->withErrors($transl_errors)@endif
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  {{$modelFullName}}  $record 
     * @return Response
     */
    public function show({{$modelFullName}} $record) {
        $module = {{$modelFullName}}::relatedModule();
        return View::make('thor::backend.{{$plural}}.show', compact('record', 'module'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{$modelFullName}}  $record 
     * @return Response
     */
    public function edit({{$modelFullName}} $record) {
        if (is_null($record)) {
            return Redirect::route('backend.{{$plural}}.index');
        }
        $module = {{$modelFullName}}::relatedModule();

        return View::make('thor::backend.{{$plural}}.edit', compact('record', 'module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  {{$modelFullName}}  $record 
     * @return Response
     */
    public function do_edit({{$modelFullName}} $record) {
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
        $transl_record = new {{$modelFullName}}Text();
        $transl_errors = array();
        @endif

        if ($record->validate($input, null, $this->record->getValidationUpdatingRules())) {
            $record->update($input);
            @if($isTranslatable)if ($transl_record->validate($transl_input, null, $transl_record->getValidationUpdatingRules())) {
            
                // Save translation
                if (Input::get('translation.id')) {
                    $transl_record = {{$modelFullName}}Text::find(Input::get('translation.id'));
                    $transl_record->update($transl_input);
                } else {
                    $transl_record = $transl_record->create(array_merge(array('language_id' => \Lang::id(), '{{$singular}}_id' => $record->id), $transl_input));
                }
            @endif
            return Redirect::route('backend.{{$plural}}.edit', $record->id)
                    ->with('info_message', '{{ucfirst($singular)}} updated successfully.');
            @if($isTranslatable)
            }else{
                $transl_errors = $transl_record->errors();
            }
            @endif
        }
        
        return Redirect::route('backend.{{$plural}}.edit', $record->id)
                        ->withInput()
                        ->withErrors($record->errors())
                        @if($isTranslatable)->withErrors($transl_errors)@endif
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{$modelFullName}}  $record 
     * @return Response
     */
    public function do_delete({{$modelFullName}} $record) {
        $record->delete();

        return Redirect::route('backend.{{$plural}}.index');
    }

}
