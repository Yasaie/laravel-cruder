<?php
/**
 * @package     laravel-cruder
 * @author      Payam Yasaie <payam@yasaie.ir>
 * @copyright   2019-07-13
 */

namespace Yasaie\Cruder;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Yasaie\Support\Yalp;

use Yasaie\Helper\Y;
use Yasaie\Paginate\Helper;

/**
 * Class    Crud
 *
 * @author  Payam Yasaie <payam@yasaie.ir>
 * @since   2019-08-22
 *
 * @package Yasaie\Cruder
 */
class Crud
{

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-19
     *
     * @param               $items
     * @param array         $heads
     *      [
     *          [
     *              'name' => 'name',        # required
     *              'get' => 'get',          # default is name
     *              'sortable' => true,      # default false
     *              'searchable' => 'name',  # default false
     *              'clickable' => true',    # default false
     *              'hidden' => true,        # default false
     *              'append' => 'string'     # default is ''
     *          ]
     *      ]
     * @param int           $per_page
     * @param string|null   $sort_by
     *
     * @return Factory|View
     */
    static public function all($items, array $heads, int $per_page, string $sort_by = null)
    {
        $heads_collect = collect($heads);

        $searchable = $heads_collect->pluck('searchable', 'name')
            ->filter();

        $sortable = $heads_collect->pluck('sortable', 'name')
            ->filter(function ($d) {
                return $d !== null;
            });
        $sortable = $searchable->merge($sortable)
            ->filter()
            ->keys();

        # Custom fields
        $search = request()->search;
        $column = request()->column;
        $sort = request()->sort ?: $sort_by;
        $sort = str_replace('_desc', '', $sort, $desc);

        # if is sortable sort
        if ($sortable->contains($sort)) {
            $items = $items->orderBy($sort, $desc ? 'desc' : 'asc');
        }

        # if search was defined
        if ($search) {
            $search_column = $column ? [$searchable[$column]] : $searchable->toArray();

            $text = 'CONCAT(COALESCE('
                . implode(", ' '), COALESCE(", $search_column)
                . ", ' ')) REGEXP CONCAT('(', ?, ')')";

            $items->whereRaw($text, compact('search'));
        }

        # paginate
        $paginate = $items->paginate($per_page)->appends(request()->all());

        # get final items
        $items = Yalp::flatten($paginate->items(), $heads);

        return view('Cruder::page.all')
            ->with(compact('heads', 'sort', 'desc', 'items', 'paginate', 'sortable', 'searchable'));
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-21
     *
     * @param int|\Eloquent  $item
     * @param array          $heads
     *      [
     *          [
     *              'name' => 'name',        # required
     *              'get' => 'get',          # default is name
     *              'link' => [
     *                  'route' => 'admin.route.name',
     *                  'search' => 'search',
     *                  'column' => 'column'
     *              ]
     *              'append' => 'string'     # default is ''
     *          ]
     *      ]
     * @param string         $model
     * @param array          $load
     *
     * @return Factory|View
     */
    static public function show($item, array $heads, string $model = '', array $load = [])
    {
        if (!is_object($item)) {
            $item = $model::find($item);
        }

        if (!$item) {
            abort(404);
        }

        if ($load) {
            $item = $item->load($load);
        }

        $item = Yalp::flatten([$item], $heads)->first();

        return view('Cruder::page.show')
            ->with(compact('item', 'heads'));
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-22
     *
     * @param array  $inputs
     * @param array  $locales
     * @param string $form_action
     * @param int    $form_id
     *
     * @return Factory|View
     */
    static public function form(array $inputs, array $locales = [], string $form_action = '', int $form_id = 0)
    {
        if (!$form_action) {
            $form_action = \Request::route()->parameters
                ? 'update' : 'index';
        }

        $form_id = $form_id ?: current(\Request::route()->parameters);

        return view('Cruder::page.form')
            ->with(compact('inputs', 'locales', 'form_action', 'form_id'));
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-22
     *
     * @param        $item
     * @param string $model
     *
     * @return array
     */
    static public function destroy($item, string $model = '')
    {
        if (!is_object($item)) {
            $item = $model::find($item);
        }

        if ($item) {
            $result = $item->delete();
        } else {
            $result = 'Not Found';
        }

        return compact('result');
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     * @since   2019-08-22
     *
     * @param               $item
     * @param array|string  $requests
     * @param string        $collection
     */
    static public function upload($item, $requests, string $collection)
    {
        $requests = is_array($requests)
            ? $requests
            : explode(',', $requests);

        foreach ($requests as $r) {
            $file = \Auth::user()->media()->find($r);
            if ($file) {
                $file->move($item, $collection);
            }
        }
    }

    /**
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $items
     * @param $heads
     * @param $sort_by
     * @param $perPage
     * @param array $load
     *
     * @return Factory|View
     *@package index
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
     * @author  Payam Yasaie <payam@yasaie.ir>
     *
     * @param $inputs
     * @param null $multilang
     * @param null $form_action
     * @param null $form_id
     *
     * @return Factory|View
     *@package create
     */
    static public function create($inputs, $multilang = null, $form_action = null, $form_id = null)
    {
        return self::form($inputs, $multilang ?: [], $form_action ?: '', $form_id ?: 0);
    }

}