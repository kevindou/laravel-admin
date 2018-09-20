<?php

namespace App\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use App\Repositories\Admin\MenuRepository;

/**
 * 获取面包屑
 *
 * Class CategoryComposer
 * @package App\Composers
 */
class BreadCrumbsComposer
{
    /**
     * @var MenuRepository
     */
    private $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // 请求路径
        $uri  = Route::current()->uri();
        $uri  = '/' . trim($uri, '/');
        $uris = [$uri];

        // 其他页面路由
        if ($active_menu = $view['__active_menu__'] ?? '') {
            $uris[] = '/' . trim($active_menu, '/');
        }

        // 获取到菜单ID
        $menu = $breadCrumb = [];
        foreach ($uris as $uri) {
            if ($menu = $this->menuRepository->findOne(['url' => $uri])) {
                break;
            }
        }

        $title       = $view['title'] ?? '';
        $description = $view['description'] ?? '';

        if ($menu) {
            $title        = $title ?: $menu['name'];
            $breadCrumb[] = array_only($menu, ['id', 'name', 'url']);
            if ($parent_menu = $this->menuRepository->findOne(['id' => array_get($menu, 'parent')])) {
                array_unshift($breadCrumb, array_only($parent_menu, ['id', 'name', 'url']));
                $description = $description ?: $parent_menu['name'];
            }
        }

        $menu_ids = array_column($breadCrumb, 'id');
        $view->with(compact('breadCrumb', 'title', 'description', 'menu_ids'));
    }
}