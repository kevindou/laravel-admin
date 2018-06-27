<?php

namespace App\Composers;

use App\Repositories\Menu\MenuRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

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
        $active_menu = isset($view['__active_menu__']) ? $view['__active_menu__'] : '';
        if ($active_menu) {
            $uris[] = '/' . trim($active_menu, '/');
        }

        // 获取到菜单ID
        $cate_id = 0;
        $type    = $this->getLoginUser('type') ?? env('PROJECT_TYPE');
        foreach ($uris as $uri) {
            if ($cate_id = $this->menuRepository->hasCateByName($uri, $type)) {
                break;
            }
        }

        // 注入变量
        if ($cate_id) {
            $cate_info        = $this->menuRepository->getCateInfo($cate_id);
            $parent_cate_info = $this->menuRepository->getCateInfo($cate_info['parent_cate_id']);
            $view->with('title', $cate_info['cate_name']);
            $view->with('cate', $parent_cate_info['cate_name']);
            $view->with('sub_cate', $cate_info['cate_name']);
        } else {
            $view->with('title', 'VeryStar');
            $view->with('cate', 'VeryStar');
            $view->with('sub_cate', '请设置__active_menu__');
        }
    }
}