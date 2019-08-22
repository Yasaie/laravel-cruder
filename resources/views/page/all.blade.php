@extends('Cruder::layout')

@section('title', $title)

@section('body')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <form class="row" id="all-table">

                        <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                            <div class="row">
                                <div class="col-md-6">
                                    @can("$route.create")
                                        <a href="{{route("$route.create")}}" class="btn btn-success btn-sm w-100">
                                            <i class="fa fa-file"></i> @lang('Cruder::crud.add')
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                            <div class="badge badge-dark py-2 w-100">
                                <span class="font-weight-normal">@lang("Cruder::crud.results") : </span>
                                <span class="text-warning position-relative"
                                      style="font-size: 1.4em; top: 1px"> {{$paginate->total()}}</span>

                                @if($paginate->total())
                                    <span class="fa fa-hashtag text-warning"></span>
                                    <span style="font-size: 1.1em">{{$paginate->firstItem()}}</span>
                                    @lang('Cruder::crud.to')
                                    <span style="font-size: 1.1em">{{$paginate->lastItem()}}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-2 d-flex mb-2 mb-md-0">
                            <div class="input-group-sm d-flex w-100">
                                <label for="rows" class="w-50 m-2 small">سطرها:</label>
                                <select name="rows" id="rows" class="form-control" onchange="document.getElementById('all-table').submit()">
                                    @php
                                        $rows = [15, 25, 50, 100];
                                    @endphp
                                    @foreach($rows as $row)
                                        <option {{ $paginate->perPage() == $row ? 'selected' : '' }}>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            @if(isset($searchable) and $searchable->count())
                                <div class="input-group input-group-sm w-100">

                                    @if($searchable->count() > 1)
                                        <select name="column" class="form-control mx-0 mx-sm-2"
                                                style="max-width: 130px;">
                                            <option value="">@lang('Cruder::crud.all')</option>
                                            @foreach($searchable as $key => $srb)
                                                <option value="{{$key}}" {{ request()->column == $key ? 'selected' : '' }}>@lang('model.' . $key)</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <input type="text" name="search" class="form-control float-right"
                                           placeholder="@lang('Cruder::crud.search')"
                                           value="{{request()->search}}">
                                    <div class="input-group-append">
                                        @if(request()->search)
                                            @php
                                                $cancel = array_filter(request()->except('search'));
                                            @endphp
                                            <a href="{{$cancel ? '?' . http_build_query($cancel) : url()->current()}}"
                                               type="submit"
                                               class="btn btn-default">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        @endif
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>

                                </div>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped table-hover dataTable">
                        <thead>
                        <tr>
                            @foreach($heads as $head)
                                @if(!isset($head['hidden']) or !$head['hidden'])
                                    @if($sortable->contains($head['name']))
                                        <th class="sorting{{$sort == $head['name'] ? ($desc ? '_desc' : '_asc') : null}}">
                                            @php
                                                $sorting = request()->all();
                                                $sorting['sort'] = $head['name'] .
                                                    (($sort == $head['name']) ? ($desc ? null : '_desc') : null);
                                            @endphp
                                            <a href="?{{http_build_query($sorting)}}">
                                                @lang('model.' . $head['name'])
                                            </a>
                                        </th>
                                    @else
                                        <th>
                                            @lang('model.' . $head['name'])
                                        </th>
                                    @endif
                                @endif
                            @endforeach
                            @canany(["$route.show", "$route.edit", "$route.destroy"])
                                <th class="text-center">@lang('Cruder::crud.actions')</th>
                            @endcanany
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                @foreach($heads as $head)
                                    @if(!isset($head['hidden']) or !$head['hidden'])
                                        @php
                                            $text = $item->{$head['name']};
                                            if ($text != '') :
                                                if (isset($head['append'])) :
                                                    $text .= $head['append'];
                                                endif;
                                                if (isset($head['clickable'])) :
                                                    $searching = request()->all();
                                                    $searching['search'] = $text;
                                                    $searching['column'] = $head['name'];
                                                    $text = "<a href='?" . http_build_query($searching) . "'>{$text}</a>";
                                                endif;
                                            else:
                                                $text = '-';
                                            endif;
                                        @endphp
                                        <td>{!! $text !!}</td>
                                    @endif
                                @endforeach

                                @canany(["$route.show", "$route.edit", "$route.destroy"])
                                    <td class="text-center">
                                        @can("$route.show")
                                            <a href="{{route("$route.show", $item->id)}}"
                                               class="btn btn-info btn-sm fa fa-eye"></a>
                                        @endcan
                                        @can("$route.edit")
                                            <a href="{{route("$route.edit", $item->id)}}"
                                               class="btn btn-success btn-sm fa fa-pencil"></a>
                                        @endcan
                                        @can("$route.destroy")
                                            <button onclick="deleteItem({{$item->id}})"
                                                    class="btn btn-danger btn-sm fa fa-trash"></button>
                                        @endcan
                                    </td>
                                @endcanany

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($paginate->hasPages())
                <div class="d-flex justify-content-center card flex-row">
                    <div class="card-header">
                        {{ $paginate->render() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{asset('vendor/cruder/js/delete.min.js')}}"></script>
@endsection