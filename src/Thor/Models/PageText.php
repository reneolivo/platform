<?php

namespace Thor\Models;

class PageText extends BaseText
{

    protected $table = 'page_texts';

    /**
     * Alias for master()
     * Returns the associated page
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        // in: foreach (Thor\Models\PageText::with('page')->get() as $page) // use eager loading!
        return $this->master();
    }

}
