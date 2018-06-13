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
        $this->adminRepository->findOne(2, ['*', 'roles' => ['*']]);
    }
}