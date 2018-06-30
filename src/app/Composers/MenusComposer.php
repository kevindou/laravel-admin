<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30 0030
 * Time: 下午 10:34
 */

namespace App\Composers;

use App\Repositories\Admin\MenuRepository;
use Illuminate\Contracts\View\View;

class MenusComposer
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
        $view->with('menus', $this->menuRepository->getPermissionMenus());
    }
}