<?php

namespace Thor\Models\Behaviours;

use Carbon\Carbon;

/**
 * Implementation of IPublishable interface
 * @property timestamp|Carbon $published_at
 * @todo Implement interface
 */
trait TPublishable
{

    public function publish($at = null)
    {
        if (empty($at)) {
            $at = time();
        }
        $this->published_at = Carbon::createFromTimestamp($at);
    }

    public function unpublish()
    {
        $this->published_at = null;
    }

    public function isPublished()
    {
        return ((!empty($this->published_at)) and ( !$this->isFuturePublication()));
    }

    public function isPastPublication()
    {
        if (empty($this->published_at)) {
            return false;
        }
        return Carbon::createFromTimestamp(strtotime($this->published_at))->isFuture();
    }

    public function isFuturePublication()
    {
        if (empty($this->published_at)) {
            return false;
        }
        return Carbon::createFromTimestamp(strtotime($this->published_at))->isPast();
    }

    /**
     * 
     * @param \DB $query
     */
    public static function scopePublished($query)
    {
        return $this->pastPublications();
    }

    public static function scopeUnpublished($query)
    {
        return $query->whereRaw('(published_at is null)');
    }

    public static function scopeFuturePublications($query)
    {
        return $query->whereRaw('(published_at is not null) AND (published_at > ?)', array(date('Y-m-d H:i:s')));
    }

    public static function scopePastPublications($query)
    {
        return $query->whereRaw('(published_at is not null) AND (published_at <= ?)', array(date('Y-m-d H:i:s')));
    }

}
