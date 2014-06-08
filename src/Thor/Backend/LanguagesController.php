<?php
namespace Thor\Backend;

use View,
    Redirect,
    Validator,
    Form;
/*
|--------------------------------------------------------------------------
| \Thor\Models\Language backend controller
|--------------------------------------------------------------------------
|
| This is a default Thor CMS backend controller template for resource management.
| Feel free to change it to your needs.
|
*/
class LanguagesController extends Controller {

    /**
     * Repository
     *
     * @var \Thor\Models\Language     */
    protected $language;

    public function __construct(\Thor\Models\Language $language) {
        $this->language = $language;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $languages = $this->language->all();

        return View::make('thor::backend.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('thor::backend.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Form::allInput();
        
        if ($this->language->validate($input)) {
            $this->language->create($input);
            return Redirect::route('backend.languages.index');
        }

        return Redirect::route('backend.languages.create')
                        ->withInput()
                        ->withErrors($this->language->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\Language  $language 
     * @return Response
     */
    public function show(\Thor\Models\Language $language) {

        return View::make('thor::backend.languages.show', compact('language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Language  $language 
     * @return Response
     */
    public function edit(\Thor\Models\Language $language) {

        if (is_null($language)) {
            return Redirect::route('backend.languages.index');
        }

        return View::make('thor::backend.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Language  $language 
     * @return Response
     */
    public function do_edit(\Thor\Models\Language $language) {
        $input = Form::allInput();

        if ($language->validate($input)) {
            $language->update($input);

            return Redirect::route('backend.languages.edit', $language->id);
        }
        
        return Redirect::route('backend.languages.edit', $language->id)
                        ->withInput()
                        ->withErrors($language->errors())
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\Language  $language 
     * @return Response
     */
    public function do_delete(\Thor\Models\Language $language) {
        $language->delete();

        return Redirect::route('backend.languages.index');
    }

}
