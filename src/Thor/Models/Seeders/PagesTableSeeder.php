<?php

namespace Thor\Models\Seeders;

use Seeder,
    Thor\Models\Page,
    Thor\Models\PageText;

class PagesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('page_texts')->delete();
        \DB::table('pages')->delete();

        $page = Page::create(array('taxonomy' => 'page', 'view' => 'default', 'sorting' => 1));
        if ($page->exists()) {
            PageText::create(array('page_id' => $page->id, 'language_id' => 1, 'title' => 'Homepage', 'slug'=>'index'));
        }

        $page = Page::create(array('taxonomy' => 'page', 'view' => '404', 'sorting' => 2));
        if ($page->exists()) {
            PageText::create(array('page_id' => $page->id, 'language_id' => 1, 'title' => 'Error', 'slug'=>'error'));
        }
    }

}
