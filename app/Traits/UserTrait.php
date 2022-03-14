<?php

namespace App\Traits;

trait UserTrait
{
    public function saveWithUserId()
    {
        $this->attributes['user_id'] = auth()->user()->id;
        $this->save();
    }
}
