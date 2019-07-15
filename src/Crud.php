<?php
/**
 * @package     laravel-cruder
 * @author      Payam Yasaie <payam@yasaie.ir>
 * @copyright   2019-07-13
 */

namespace Yasaie\Cruder;

use Yasaie\Helper\Y;
use Yasaie\Paginate\Helper;

class Crud
{
    /**
     * @package index
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $items
     * @param $heads
     * @param $request
     * @param $sort_by
     * @param $perPage
     *
     * @return mixed
     */
    public static function index($items, $heads, $request, $sort_by, $perPage)
    {
        # Url query requested
        $query = [
            'search' => $request->search,
            'sort' => $request->sort,
            'desc' => $request->desc
        ];

        # Custom fields
        $search = $request->search;
        $sort = $request->sort ?: $sort_by;
        $desc = $request->desc ? 1 : 0;

        # flatten and Search in model if search requested
        $items = Y::flattenItems($items, $heads, $search);
        # Sort and desc/asc items
        $items = $items->sortBy($sort, SORT_NATURAL, $desc);
        # Paginate items
        $pages = Helper::paginate($items, $request->page, $perPage);

        return view('admin.crud.table')
            ->with(compact('heads', 'sort', 'desc', 'search', 'items', 'pages', 'query'));
    }

    /**
     * @package show
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $item
     * @param $heads
     *
     * @return mixed
     */
    public static function show($item, $heads)
    {
        return view('admin.crud.show')
            ->with(compact('item', 'heads'));
    }

    /**
     * @package create
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $inputs
     * @param null $multilang
     *
     * @return mixed
     */
    public static function create($inputs, $multilang = null)
    {
        return view('admin.crud.create')
            ->with(compact('inputs', 'multilang'));
    }
}