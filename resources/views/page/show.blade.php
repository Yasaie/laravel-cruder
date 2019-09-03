@extends('Cruder::layout')

@section('title', $title)

@section('body')

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="form-group float-left">
                        @can("$route.edit")
                            <a href="{{route("$route.edit", $item->id)}}" class="btn btn-success btn-sm mx-1">
                                <i class="fa fa-pencil"></i>
                                @lang('Cruder::crud.edit')
                            </a>
                        @endcan
                        @can("$route.destroy")
                            <button onclick="deleteItem({{$item->id}})" class="btn btn-danger btn-sm mx-1">
                                <i class="fa fa-trash"></i>
                                @lang('Cruder::crud.delete')
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                        @foreach($heads as $head)
                            @php
                                $text = $item->{$head['name']};
                                if ($text != '') :
                                    if (isset($head['append'])) :
                                        $text .= $head['append'];
                                    endif;
                                    if (isset($head['link'])) :
                                        $searching['column'] = $head['link']['column'];
                                        $searching['search'] = $item->{$head['link']['search']};
                                        $text = "<a href='"
                                            . route($head['link']['route'])
                                            . "?"
                                            . http_build_query($searching)
                                            . "'>{$text}</a>";
                                    endif;
                                    if (isset($head['string'])) :
                                        $text = nl2br($text);
                                    endif;
                                else:
                                    $text = '-';
                                endif;
                            @endphp
                            <tr>
                                <th class="w-25">
                                    @lang('model.' . $head['name'])
                                </th>
                                <td>
                                    {!! $text !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{asset('vendor/cruder/js/delete.min.js')}}"></script>
@endsection