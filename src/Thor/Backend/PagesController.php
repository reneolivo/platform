<?php
namespace Thor\Admin;

use View,
    Redirect,
    Validator,
    Form;
/*
|--------------------------------------------------------------------------
| \Thor\Models\Page admin controller
|--------------------------------------------------------------------------
|
| This is a default Thor Framework admin controller template for resource management.
| Feel free to change it to your needs.
|
*/
class PagesController extends \Controller {

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

        return View::make('admin::pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('admin::pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create() {
        $input = Form::allInput();
        $validation = Validator::make($input, \Thor\Models\Page::$rules);

        if ($validation->passes()) {
            $this->page->create($input);

            return Redirect::route('admin.pages.index');
        }

        return Redirect::route('admin.pages.create')
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

        return View::make('admin::pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function edit(\Thor\Models\Page $page) {

        if (is_null($page)) {
            return Redirect::route('admin.pages.index');
        }

        return View::make('admin::pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Page  $page 
     * @return Response
     */
    public function do_edit(\Thor\Models\Page $page) {
        $input = Form::allInput();
        $validation = Validator::make($input, \Thor\Models\Page::$rules);

        if ($validation->passes()) {
            $page->update($input);

            return Redirect::route('admin.pages.edit', $page->id);
        }
        
        return Redirect::route('admin.pages.edit', $page->id)
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

        return Redirect::route('admin.pages.index');
    }

}
