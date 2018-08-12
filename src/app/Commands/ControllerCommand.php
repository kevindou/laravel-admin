<?php
/**
 *
 * Model.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/27 14:13
 * Editor: created by PhpStorm
 */

namespace App\Commands;

use Illuminate\Support\Facades\DB;

/**
 * Class ControllerCommand
 * @package App\Commands
 */
class ControllerCommand extends Command
{
    /**
     * @var string 基础目录
     */
    protected $basePath = 'app/Http/Controllers';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:controller {--name=} {--r=} {--request=} {--pk=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成 Controller {--name=} 指定名称 
     {--r=} 指定 Repository 需要从 Repositories 目录开始; 默认使用控制器同名 Repositories
     {--request=} 指定 request 目录; 需要从 Requests 目录开始; 默认使用控制器命名空间';

    public function handle()
    {
        if (!$namespace = $this->option('name')) {
            $this->error('请输入名称');
            return;
        }

        $pk         = $this->option('pk') ?: 'id';
        $file_name  = $this->getPath($namespace . '.php', false);
        $view       = str_replace(['/', 'controller'], ['.', ''], strtolower($namespace));
        $namespace  = explode('/', $namespace);
        $class_name = array_pop($namespace);
        $namespace  = $namespace ? '\\' . implode('\\', $namespace) : '';

        // 请求 request 目录
        if ($request = $this->option('request')) {
            $request = '/' . ltrim($request, '/');
        } else {
            $request = $namespace;
        }

        // repository
        if ($repository = $this->option('r')) {
            $arr_repository       = explode('/', $repository);
            $repository           = array_pop($arr_repository);
            $repository_namespace = $arr_repository ? '\\' . implode('\\', $arr_repository) : '';
        } else {
            $repository           = str_replace('Controller', '', $class_name) . 'Repository';
            $repository_namespace = $namespace;
        }

        $this->render($file_name, [
            'namespace'            => $namespace,
            'request_namespace'    => str_replace('/', '\\', $request),
            'class_name'           => $class_name,
            'repository'           => $repository,
            'repository_namespace' => $repository_namespace,
            'primary_key'          => $pk,
            'view'                 => $view . '.index',
        ]);
    }

    public function getRenderHtml()
    {
        return <<<html
<?php
     
namespace App\Http\Controllers{namespace};

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests{request_namespace}\DestroyRequest;
use App\Http\Requests{request_namespace}\StoreRequest;
use App\Http\Requests{request_namespace}\UpdateRequest;
use App\Repositories{repository_namespace}\{repository};

class {class_name} extends Controller
{
    public function __construct({repository} \$repository)
    {
        parent::__construct();
        \$this->repository = \$repository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('{view}');
    }

    /**
     * 添加数据
     *
     * @param StoreRequest \$request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest \$request)
    {
        return \$this->sendJson(\$this->repository->create(\$request->all()));
    }

    /**
     * 修改数据
     *
     * @param UpdateRequest \$request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest \$request)
    {
        return \$this->sendJson(\$this->repository->update(\$request->input('{primary_key}'), \$request->all()));
    }

    /**
     * 删除数据
     *
     * @param DestroyRequest \$request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest \$request)
    {
        return \$this->sendJson(\$this->repository->delete(\$request->input('{primary_key}')));
    }
}
html;
    }
}