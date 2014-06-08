<?php echo '<?php'; ?>

namespace {{$controller['classNamespace']}};

use View,
    Redirect,
    Validator,
    Form;
/*
|--------------------------------------------------------------------------
| {{$model['classFullName']}} admin controller
|--------------------------------------------------------------------------
|
| This is a default Thor Framework admin controller template for resource management.
| Feel free to change it to your needs.
|
*/
class {{$controller['className']}} extends {{Config::get('generators::controller_extends')}} {

    /**
     * Repository
     *
     * @var {{$model['classFullName']}}
     */
    protected ${{$singular}};

    public function __construct({{$model['classFullName']}} ${{$singular}}) {
        $this->{{$singular}} = ${{$singular}};
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        ${{$plural}} = $this->{{$singular}}->all();

        return View::make('admin::{{$plural}}.index', compact('{{$plural}}'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('admin::{{$plural}}.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Form::allInput();

        if ($this->{{$singular}}->validate($input)) {
            $this->{{$singular}}->create($input);

            return Redirect::route('admin.{{$plural}}.index');
        }

        return Redirect::route('admin.{{$plural}}.create')
                        ->withInput()
                        ->withErrors($this->{{$singular}}->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  {{$model['classFullName']}}  ${{$singular}} 
     * @return Response
     */
    public function show({{$model['classFullName']}} ${{$singular}}) {

        return View::make('admin::{{$plural}}.show', compact('{{$singular}}'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  {{$model['classFullName']}}  ${{$singular}} 
     * @return Response
     */
    public function edit({{$model['classFullName']}} ${{$singular}}) {

        if (is_null(${{$singular}})) {
            return Redirect::route('admin.{{$plural}}.index');
        }

        return View::make('admin::{{$plural}}.edit', compact('{{$singular}}'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  {{$model['classFullName']}}  ${{$singular}} 
     * @return Response
     */
    public function do_edit({{$model['classFullName']}} ${{$singular}}) {
        $input = Form::allInput();

        if (${{$singular}}->validate($input)) {
            ${{$singular}}->update($input);

            return Redirect::route('admin.{{$plural}}.edit', ${{$singular}}->id);
        }
        
        return Redirect::route('admin.{{$plural}}.edit', ${{$singular}}->id)
                        ->withInput()
                        ->withErrors(${{$singular}}->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  {{$model['classFullName']}}  ${{$singular}} 
     * @return Response
     */
    public function do_delete({{$model['classFullName']}} ${{$singular}}) {
        ${{$singular}}->delete();

        return Redirect::route('admin.{{$plural}}.index');
    }

}
