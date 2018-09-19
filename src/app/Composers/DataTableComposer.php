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
        $view->with([
            'tableCss'        => [
                'admin-assets/plugins/datatables/css/dataTables.bootstrap.min.css',
            ],
            'tableJavascript' => [
                'admin-assets/plugins/datatables/js/jquery.dataTables.min.js',
                'admin-assets/plugins/datatables/js/dataTables.bootstrap.min.js',
                'admin-assets/plugins/jquery-validation/jquery.validate.min.js',
                'admin-assets/plugins/jquery-validation/validate.message.js',
                'admin-assets/plugins/table/jquery.meTables.js',
                'admin-assets/plugins/table/laravel.admin.js',
            ],
        ]);
    }
}