<?php

namespace Thor\Backend;

use View,
    Redirect,
    Input;

/*
  |--------------------------------------------------------------------------
  | Backend controller for \Thor\Models\Page
  |--------------------------------------------------------------------------
  |
  | This is a default Thor CMS backend controller template for resource management.
  | Feel free to change it to your needs.
  |
 */

class PagesController extends \Thor\Backend\Controller
{

    /**
     * Model repository
     *
     * @var \Thor\Models\Page     */
    protected $record;

    public function __construct(\Thor\Models\Page $record)
    {
        $this->record = $record;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->record->all();

        return View::make('thor::backend.pages.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('thor::backend.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_create()
    {
        $input = Input::all();
        $transl_record = new \Thor\Models\PageText();
        $transl_input = array_except(Input::get('translation'), 'id');
        $transl_errors = array();

        if ($this->record->validate($input)) {
            $record = $this->record->create($input);
            if ($transl_record->validate($transl_input)) {
                $transl_record = $transl_record->create(array_merge(array('language_id' => \Lang::id(), 'page_id' => $record->id), $transl_input));
                return Redirect::route('backend.pages.edit', $record->id);
            } else {
                $transl_errors = $transl_record->errors();
            }
        }

        return Redirect::route('backend.pages.create')
                        ->withInput()
                        ->withErrors($this->record->errors())
                        ->withErrors($transl_errors)->with('message', 'There were validation errors.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Thor\Models\Page  $record 
     * @return Response
     */
    public function show(\Thor\Models\Page $record)
    {

        return View::make('thor::backend.pages.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Thor\Models\Page  $record 
     * @return Response
     */
    public function edit(\Thor\Models\Page $record)
    {

        if (is_null($record)) {
            return Redirect::route('backend.pages.index');
        }

        return View::make('thor::backend.pages.edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thor\Models\Page  $record 
     * @return Response
     */
    public function do_edit(\Thor\Models\Page $record)
    {
        $input = array_merge(array(// for unchecked checkboxes:
            'is_https' => false,
            'is_indexable' => false,
            'is_deletable' => false,
                ), Input::all());

        $transl_input = array_merge(array(// for unchecked checkboxes:
            'is_translated' => false,
                ), array_except(Input::get('translation'), 'id'));
        $transl_record = new \Thor\Models\PageText();
        $transl_errors = array();

        if ($record->validate($input)) {
            $record->update($input);
            if ($transl_record->validate($transl_input)) {
                // Save translation
                if (Input::get('translation.id')) {
                    $transl_record = \Thor\Models\PageText::find(Input::get('translation.id'));
                    $transl_record->update($transl_input);
                } else {
                    $transl_record = $transl_record->create(array_merge(array('language_id' => \Lang::id(), 'page_id' => $record->id), $transl_input));
                }
                return Redirect::route('backend.pages.edit', $record->id);
            } else {
                $transl_errors = $transl_record->errors();
            }
        }

        return Redirect::route('backend.pages.edit', $record->id)
                        ->withInput()
                        ->withErrors($record->errors())
                        ->withErrors($transl_errors)->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thor\Models\Page  $record 
     * @return Response
     */
    public function do_delete(\Thor\Models\Page $record)
    {
        $record->delete();

        return Redirect::route('backend.pages.index');
    }

}
