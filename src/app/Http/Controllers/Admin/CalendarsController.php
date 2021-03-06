<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Calendar;
use App\Repositories\Admin\CalendarRepository;
use App\Http\Requests\Admin\Calendars\DestroyRequest;
use App\Http\Requests\Admin\Calendars\StoreRequest;
use App\Http\Requests\Admin\Calendars\UpdateRequest;
use Illuminate\Http\Request;

class CalendarsController extends Controller
{
    public function __construct(CalendarRepository $calendarRepository)
    {
        parent::__construct();
        $this->repository = $calendarRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // 默认状态信息
        $status     = Calendar::getStatus();
        $timeStatus = Calendar::getTimeStatus();
        $colors     = Calendar::$arrColor;

        // 载入视图
        return view('admin::calendars.index', compact('status', 'timeStatus', 'colors'));
    }

    /**
     * 添加数据
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->sendJson($this->repository->create($request->all()));
    }

    /**
     * 修改数据
     *
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        return $this->sendJson($this->repository->update($request->input('id'), $request->all()));
    }

    /**
     * 删除数据
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        return $this->sendJson($this->repository->delete($request->input('id')));
    }

    /**
     * 我的日程管理显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function self()
    {
        view()->share([
            'title'           => trans('我的日程'),
            '__active_menu__' => 'admin/calendars/index'
        ]);

        // 默认状态信息
        $status     = Calendar::getStatus();
        $timeStatus = Calendar::getTimeStatus();
        $colors     = Calendar::$arrColor;

        // 查询数据
        $calendars = $this->repository->findAll(['status' => 0, 'orderBy' => 'id desc']);

        // 载入视图
        return view('admin::calendars.self', compact('status', 'timeStatus', 'colors', 'calendars'));
    }

    /**
     * 查找我的日程事件信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function events(Request $request)
    {
        // 查询数据
        $all = $this->repository->findAll([
            'created_id' => 1,
            'start:egt'  => $request->input('start') . ' 00:00:00',
            'end:elt'    => $request->input('end') . ' 23:59:59'
        ]);

        return response()->json($all);
    }
}
