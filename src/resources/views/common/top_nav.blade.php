@if ($menus)
    <ul class="nav navbar-nav navbar-top-links navbar-left admin-top-nav" id="admin-nav">
        @foreach($menus as $item)
            <li role="presentation" class="dropdown @if(in_array($item['id'], $menu_ids)) active @endif">
                <a href="{{ url(array_get($item, 'url')) }}">
                    <i class="fa {{ array_get($item, 'icon') }}"></i>
                    <span>
                        {{ trans(array_get($item, 'name')) }}
                    </span>
                </a>
                @if (!empty($item['children']))
                    <div class="dropdown-menu" style="width: {{ count($item['children']) * 170 + 10 }}px">
                        @foreach ($item['children'] as $children)
                            <ul class="pull-left text-left admin-ul">
                                <li class="first">
                                    <i class="fa {{  $children['icon'] ?: 'fa-circle-o' }} fa-fw"></i>
                                    {{ $children['name'] }}
                                </li>
                                @if (!empty($children['children']))
                                    @foreach ($children['children'] as $child)
                                        <li>
                                            <a href="{{ url($child['url']) }}">
                                                <i class="fa {{  $child['icon'] ?: 'fa-circle-o' }} fa-fw"></i>
                                                {{ $child['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @endforeach
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif