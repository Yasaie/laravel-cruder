<aside class="main-sidebar elevation-4 sidebar-dark-info">

    <a href="" class="brand-link">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>


    <div class="sidebar">

        <div class="user-panel px-2 pt-1 mt-2 d-flex" style="background: #41484f;">
            <div class="image">
                <svg class="img-circle" height="40" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M512 76.893274c240.301971 0 435.106726 194.803731 435.106726 435.106726S752.301971 947.106726 512 947.106726c-240.302995 0-435.106726-194.803731-435.106726-435.106726s194.803731-435.106726 435.106726-435.106726z" fill="#17a2b8"/>
                    <path d="M542.112832 571.787688c-4.055362-0.338714-7.922437 1.058099-10.853186 3.758604-1.958608 1.815345-4.184299 4.335748-6.65456 7.187702-4.084015 4.76042-8.31027 9.685593-12.502755 10.910491h-0.007164c-4.177136-1.216711-8.411577-6.15007-12.761651-11.213389-2.26867-2.62171-4.472871-5.127787-6.445806-6.928807-2.930749-2.686179-6.856152-4.054339-10.803043-3.7453-90.022293 6.747681-159.943782 37.002752-187.332573 81.423451-3.172249 5.613858-58.915831 105.941867-53.314253 199.585641 74.27668 59.052954 168.297031 94.339622 270.561136 94.339621 102.267175 0 196.288548-35.287691 270.566252-94.343715 5.559623-95.708805-52.686966-198.771089-53.646828-200.438055-26.898627-43.606147-96.741321-73.701582-186.805569-80.536244z m-3.623528 266.436188a10.725272 10.725272 0 0 1-1.288342 4.824889l-15.692401 29.188786c-1.886977 3.499707-5.552459 5.696746-9.513678 5.696746s-7.633864-2.196015-9.506514-5.696746l-15.699564-29.188786a10.918677 10.918677 0 0 1-1.282202-4.824889l-5.559623-209.326492a10.831696 10.831696 0 0 1 4.421706-9.016351l21.24486-15.505136a10.794857 10.794857 0 0 1 12.740162 0l21.259186 15.505136a10.80202 10.80202 0 0 1 4.429893 9.016351l-5.553483 209.326492zM509.487783 553.790802c84.174098 0 152.662959-68.496024 152.662959-152.677285 0-84.174098-68.496024-152.662959-152.662959-152.662958-84.181261 0-152.677285 68.488861-152.677285 152.662958 0 84.180238 68.488861 152.677285 152.677285 152.677285z" fill="#D6EDFE"/>
                </svg>
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
