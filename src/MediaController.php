<?php

namespace Yasaie\Cruder;

use Spatie\MediaLibrary\Models\Media;

/**
 * Class    MediaController
 *
 * @author  Payam Yasaie <payam@yasaie.ir>
 * @since   2019-09-04
 *
 * @package Yasaie\Cruder
 */
class MediaController
{

    /**
     * @package upload
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     */
    public function upload()
    {
        return \Auth::user()->addMediaFromRequest('file')
            ->toMediaCollection('draft')->id;
    }

    /**
     * @package unlink
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param null $id
     *
     * @return int
     */
    public function unlink($id = null)
    {
        if ($id)
            return Media::find($id)->delete() ? 1 : 0;
        return 0;
    }
}
