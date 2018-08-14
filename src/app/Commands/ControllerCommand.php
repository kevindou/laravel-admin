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

use App\Traits\Command\CommandTrait;

/**
 * Class ControllerCommand 生成 Controller 信息
 * @package App\Commands
 */
class ControllerCommand extends AdminCommand
{
    use CommandTrait;

    /**
     * @var string 基础目录
     */
    protected $basePath = 'app/Http/Controllers';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:controller {--name=} {--r=} {--request=} {--pk=} {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成 Controller 
     {--table=}   指定表名称 [ 指定该参数，生成视图文件 ]  
     {--name=}    指定名称 可以带命名空间 [ --name=Home/IndexController 或者 Home\\IndexController ]
     {--r=}       指定 Repository 需要从 Repositories 目录开始; 默认使用控制器同名 Repository
     {--request=} 指定 request 目录; 需要从 Requests 目录开始; 默认使用控制器命名空间
     {--pk=}      指定主键名称，默认id';

    public function handle()
    {
        if (!$this->option('name')) {
            $this->error('请输入名称');
            return;
        }

        $pk = $this->option('pk');
        if ($table = $this->option('table')) {
            if (!$this->findTableExist($table)) {
                $this->error('输入的表名称不存在');
                return;
            }

            $pk = $this->findPrimaryKey($table);
        }

        $pk        = $pk ?: 'id';
        $name      = $this->handleOptionName('IndexController', 'Controller');
        $file_name = $this->getPath($name . '.php', false);
        list($namespace, $class_name) = $this->getNamespaceAndClassName($file_name, 'Controllers');
        // repository 命名空间和类名称
        list($repository_namespace, $repository) = $this->getRepositoryNamespaceAndClass($namespace, $class_name);

        $view = $this->getView($namespace, $class_name);
        $this->render($file_name, [
            'namespace'            => $namespace,
            'request_namespace'    => $this->getRequestNamespace($namespace, $class_name),
            'class_name'           => $class_name,
            'repository'           => $repository,
            'repository_name'      => camel_case($repository),
            'repository_namespace' => $repository_namespace,
            'primary_key'          => $pk,
            'view'                 => $view,
        ]);

        if ($table) {
            $this->call('admin:view', [
                '--table' => $table,
                '--path'  => str_replace('.', '/', str_replace_last('.index', '', $view)),
            ]);
        }
    }

    /**
     * 获取request 的命名空间
     *
     * @param $namespace
     * @param $class_name
     *
     * @return array|string
     */
    private function getRequestNamespace($namespace, $class_name)
    {
        // 请求 request 目录
        if (!$request = $this->option('request')) {
            if ($request = $namespace) {
                $request .= '/';
            }

            $request .= $class_name;
        }

        return '\\' . ltrim(str_replace(['/', 'Controller'], ['\\', ''], $request), '\\');
    }

    /**
     * 获取 Repository 的命名空间和类名称
     *
     * @param $namespace
     * @param $class_name
     *
     * @return array
     */
    private function getRepositoryNamespaceAndClass($namespace, $class_name)
    {
        // repository
        if ($repository = trim(str_replace('\\', '/', $this->option('r')), '/')) {
            $arr_repository       = explode('/', $repository);
            $repository           = array_pop($arr_repository);
            $repository_namespace = $arr_repository ? '\\' . implode('\\', $arr_repository) : '';
        } else {
            $repository           = str_replace('Controller', '', $class_name);
            $repository_namespace = $namespace;
        }

        // 是否Repository 结尾，不是加上
        if (!ends_with($repository, 'Repository')) {
            $repository .= 'Repository';
        }

        return [$repository_namespace, $repository];
    }

    private function getView($namespace, $class_name)
    {
        $view = str_replace(['\\', '/', 'Controller'], ['.', '.', ''], $namespace . '/' . $class_name . '.index');
        return strtolower(trim($view, '.'));
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
    /**
     * @var {repository}
     */
    private \${repository_name};
    
    public function __construct({repository} \${repository_name})
    {
        parent::__construct();
        \$this->repository = \${repository_name};
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