<div class="row">
    <div class="col-12">

        <div class="card">

            @if(isset($tabs) and $tabs)
                @php
                    $suffix = '-' . \Illuminate\Support\Str::random(4)
                @endphp
                <div class="card-header" style="border-bottom: none">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($tabs as $tab)
                            <li class="nav-item">
                                <a class="nav-link {{ collect($tabs)->first() == $tab ? 'active' : ''}}"
                                   data-toggle="tab"
                                   href="#{{ dot($tab, 'id') . $suffix }}-tab"
                                   aria-selected="true">{{ dot($tab, 'name') }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        @foreach($body as $key => $data)
                            @php
                                $tab = dot($tabs, $key . '.id');
                            @endphp
                            <div class="tab-pane fade {{ $key == 0 ? 'show active' : ''}}"
                                 id="{{ $tab . $suffix}}-tab">

                                @foreach($data as $input)
                                    <div class="form-group">
                                        @php($locale = isset($input['locale']['name']) ? $input['locale']['name'] : 'model.'. $input['name'])
                                        @php($attributes = isset($input['locale']['attributes']) ? $input['locale']['attributes'] : [])
                                        <label for="{{$input['name']}}[{{ $tab }}]">@lang($locale, $attributes)</label>
                                        @if(isset(old($input['name'])[$tab]))
                                            @php($input['value'] = old($input['name'])[$tab])
                                        @endif
                                        @php($input['name'] = $input['name'] . '[' . $tab . ']')
                                        @include('Cruder::component.' . $input['type'], $input)
                                    </div>
                                @endforeach

                            </div>
                        @endforeach
                    </div>
                </div>
            @else

                <div class="card-body">
                    @foreach($body as $input)
                        <div class="form-group">
                            @php($locale = isset($input['locale']['name']) ? $input['locale']['name'] : 'model.'. $input['name'])
                            @php($attributes = isset($input['locale']['attributes']) ? $input['locale']['attributes'] : [])
                            <label for="{{ $input['name'] }}">@lang($locale, $attributes)</label>
                            @if(old($input['name']))
                                @php($input['value'] = old($input['name']))
                            @endif
                            @include('Cruder::component.' . $input['type'], $input)
                        </div>
                    @endforeach
                </div>

            @endif

        </div>
    </div>
</div>