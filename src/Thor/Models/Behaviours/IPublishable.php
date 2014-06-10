<?php

namespace Thor\Models\Behaviours;

/**
 * Interface for models that may be published/unpublished using a 'published_at' timestamp nullable field
 * @property timestamp $published_at
 */
interface IPublishable
{

    public function publish($at = null);

    /**
     * Sets the published_at field to null (draft)
     */
    public function unpublish();

    public function isPublished();

    public function isPastPublication();

    public function isFuturePublication();

    public static function scopePublished();

    public static function scopeUnpublished();

    public static function scopeFuturePublications();

    public static function scopePastPublications();
}
