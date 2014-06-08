<?php
namespace Thor\Backend;

use View,
    Redirect,
    Validator,
    Form;
/*
|--------------------------------------------------------------------------
| \Thor\Models\Page backend controller
|--------------------------------------------------------------------------
|
| This is a default Thor CMS backend controller template for resource management.
| Feel free to change it to your needs.
|
*/
class PagesController extends Controller {

    /**
     * Repository
     *
     * @var \Thor\Models\Page     */
    protected $page;

    public function __construct(\Thor\Models\Page $page) {
        $this->page = $page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $pages = $this->page->all();

        return View::make('thor::backend.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('thor::backend.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = \Input::all();
        $validation = Validator::make($input, \Thor\Models\Page::$rules);

        if ($validation->passes()) {
            $this->page->create($input);

            return Redirect::route('backend.pages.index');
        }

        return Redirect::route('backend.pages.create')
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function show(\Thor\Models\Page $page) {

        return View::make('thor::backend.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function edit(\Thor\Models\Page $page) {

        if (is_null($page)) {
            return Redirect::route('backend.pages.index');
        }

        return View::make('thor::backend.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function do_edit(\Thor\Models\Page $page) {
        $input = \Input::all();
        $validation = Validator::make($input, \Thor\Models\Page::$rules);

        if ($validation->passes()) {
            $page->update($input);

            return Redirect::route('backend.pages.edit', $page->id);
        }
        
        return Redirect::route('backend.pages.edit', $page->id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function do_delete(\Thor\Models\Page $page) {
        $page->delete();

        return Redirect::route('backend.pages.index');
    }

}
