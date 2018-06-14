<?php
/**
 *
 * TestController.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/13 16:14
 * Editor: created by PhpStorm
 */

namespace App\Http\Controllers;


use App\Models\Admin;
use App\Repositories\AdminRepository;

class HomeController extends Controller
{
    /**
     * @var AdminRepository
     */
    private $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        var_dump($this->adminRepository->setModelCondition([
            'or'        => [
                'id:eq' => 1,
                'name'  => 'admin'
            ],
            'name'      => 'liujinxing',
            'name:like' => 123,
            'user'      => [1, 2]
        ], ['id', 'name', 'email'])->toSql());
        dd(Admin::where('id', '!=', 0)->offset(1)->limit(1)->toSql());
    }
}