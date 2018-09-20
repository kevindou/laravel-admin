<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30 0030
 * Time: 下午 12:31
 */

namespace App\Composers;

use Illuminate\Contracts\View\View;

class DataTableComposer
{
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
        $suffix = app()->isLocal() ? '.js' : '.min.js';
        $view->with([
            'tableCss'        => [
                'admin-assets/plugins/datatables/css/dataTables.bootstrap.min.css',
            ],
            'tableJavascript' => [
                'admin-assets/plugins/datatables/js/jquery.dataTables.min.js',
                'admin-assets/plugins/datatables/js/dataTables.bootstrap.min.js',
                'admin-assets/plugins/jquery-validation/jquery.validate.min.js',
                'admin-assets/laravel-admin/validate.message' . $suffix,
                'admin-assets/laravel-admin/jquery.meTables' . $suffix,
                'admin-assets/laravel-admin/laravel.admin' . $suffix,
            ],
        ]);
    }
}