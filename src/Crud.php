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
     * @param $sort_by
     * @param $perPage
     * @param array $load
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    static public function index($items, $heads, $sort_by, $perPage, $load = [])
    {
        if (!is_object($items)) {
            $items = $items::get();
        }

        if ($load) {
            $items = $items->load($load);
        }

        # get all requests
        $request = request();
        # Url query requested
        $query = [
            'search' => $request->search,
            'sort' => $request->sort,
        ];

        # Custom fields
        $search = $request->search;
        $column = $request->column;
        $sort = $request->sort ?: $sort_by;
        $sort = str_replace('_desc', '', $sort, $desc);

        # flatten and Search in model if search requested
        $items = Y::flattenItems($items, $heads, $search, $column);
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
     * @param $route
     * @param null $model
     * @param array $load
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    static public function show($item, $heads, $route = null, $model = null, $load = [])
    {
        if (!is_object($item)) {
            $item = $model::find($item);
        }

        if (!$item and $route) {
            return redirect()->route($route . '.index');
        }

        if ($load) {
            $item = $item->load($load);
        }

        return view('admin.crud.show')
            ->with(compact('item', 'heads'));
    }

    /**
     * @package create
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $inputs
     * @param null $multilang
     * @param null $form_action
     * @param null $form_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    static public function create($inputs, $multilang = null, $form_action = null, $form_id = null)
    {
        if (!$form_action) {
            $form_action = \Request::route()->parameters
                ? 'update' : 'index';
        }

        $form_id = $form_id ?: current(\Request::route()->parameters);

        return view('admin.crud.create')
            ->with(compact('inputs', 'multilang', 'form_action', 'form_id'));
    }

    /**
     * @package destroy
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $item
     * @param null $model
     *
     * @return mixed
     */
    static public function destroy($item, $model = null)
    {
        if (!is_object($item)) {
            $item = $model::find($item);
        }

        return [
            'result' => $item->delete()
        ];
    }

    /**
     * @package upload
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $item
     * @param $requests
     * @param $collection
     */
    static public function upload($item, $requests, $collection)
    {
        $requests = is_array($requests)
            ? $requests
            : explode(',', request()->images);

        foreach ($requests as $r) {
            $file = \Auth::user()->media()->find($r);
            if ($file) {
                $file->move($item, $collection);
            }
        }
    }
}