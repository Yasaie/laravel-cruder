<aside class="main-sidebar elevation-4 sidebar-dark-info">

    <a href="" class="brand-link">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>


    <div class="sidebar">

        <div class="user-panel px-2 pt-2 mt-2 d-flex" style="background: #41484f;">
            <div class="image">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}" class="img-circle" >
            </div>
            <div class="info">
                <a href="{{route('admin.user.user.show', Auth::id() ?: 0)}}"
                   class="d-block">{{Auth::user() ? Auth::user()->full_name : 'Guest'}}</a>
            </div>
        </div>


        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @foreach($menu_items as $menu)
                    @php
                        $full_route = isset($menu['route']) ? ($menu['base'] . $menu['route']) : 0;
                        if (isset($menu['child'])) :
                            $permissions = array_map(function ($m) use ($menu) {
                                return isset($m['route']) ? $menu['base'] . $m['route'] : 0;
                            }, $menu['child']);
                        else:
                            $permissions[] = $full_route;
                        endif;
                    @endphp
                    @canany($permissions)
                        <li class="nav-item {{isset($menu['child']) ? 'has-treeview' : ''}} {{Route::is($menu['base'] . '*') ? 'menu-open' : ''}}">
                            <a href="{{$full_route ? route($full_route) : '#' }}"
                               class="nav-link d-flex">
                                <i class="nav-icon fa fa-{{$menu['icon']}}"></i>
                                <p class="flex-grow-1">
                                    @if(! isset($menu['route']))
                                        <i class="right fa fa-angle-left"></i>
                                    @endif
                                    <span>{{$menu['name']}}</span>
                                </p>
                                @if(isset($menu['count']) and $menu['count'])
                                    <span class="badge badge-danger" style="margin: 3px 15px;">{{$menu['count']}}</span>
                                @endif
                            </a>
                            @if(isset($menu['child']))
                                <ul class="nav nav-treeview">
                                    @foreach($menu['child'] as $child)
                                        @php
                                            $full_route = isset($child['route']) ? ($menu['base'] . $child['route']) : 0
                                        @endphp
                                        @can($full_route)
                                            <li class="nav-item">
                                                <a href="{{$full_route ? route($full_route) : '#' }}"
                                                   class="nav-link d-flex {{Route::is($menu['base'] . $child['route']) ? 'active' : ''}}">
                                                    <i class="fa fa-circle-o nav-icon"></i>
                                                    <p class="flex-grow-1">{{$child['name']}}</p>
                                                    @if(isset($child['count']) and $child['count'])
                                                        @php
                                                            $count = is_numeric($child['count'])
                                                                ? $child['count']
                                                                : Y::dotObject(Auth::user(), $child['count']);
                                                        @endphp
                                                        @if($count)
                                                            <span class="badge badge-info" style="margin: 3px 15px;">
                                            {{$count}}
                                        </span>
                                                        @endif
                                                    @endif
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endcan
                @endforeach

            </ul>
        </nav>

    </div>

</aside>
