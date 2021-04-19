<?php


namespace App\Http\Controllers\Api\V1;


use App\Enums\TaskTargetType;
use App\Enums\UserHookType;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends ApiController
{

    public function __construct(protected TaskService $taskService)
    {
    }

    /**
     * 获取单个任务-wqdgrw
     * @queryParam hook  required 触发事件
     * @queryParam task_target  required 触发事件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTaskInfo(Request $request)
    {

        try {
            $this->validatorData($request->all(), [
                'hook' => ['required', Rule::in(UserHookType::getValues())],
                'task_target' => ['required', Rule::in(TaskTargetType::getValues())],
            ]);

            $task = $this->taskService->getTaskOrm()->where('task_target', $request->input('task_target'))->where('hook', $request->input('hook'))->first();

            return $this->response($task ? TaskResource::make($task) : null);
        } catch (\Exception $exception) {

        }


    }

    /**
     * 任务列表-rwlb
     * @queryParam hook  required  事件类型
     * @param Request $request
     */
    public function taskList(Request $request)
    {
        $orm = $this->taskService->getTaskOrm()->where('is_show', true)->orderByDesc('order')->orderBy('id');

        $hook = $request->input('hook');

        if ($hook) $orm->where('hook', $hook);

        $user = $this->user();

        if ($user) {

            $orm->with(['userTask' => fn($q) => $q->where('user_id', $user->id)]);
        }


        $list = $orm->get()->filter(function (Task $task) use ($user) {

            if (!$user) return true;


            //未充值用户
            if ($user->recharge_count <= 0 && $task->user_type == 2) {
                return false;
            }

            //已充值用户
            if ($user->recharge_count > 0 && $task->user_type == 1) {
                return false;
            }

            $userTask = $task->userTask;


            //没触发过
            if (!$userTask) return true;
            //允许重复
            if ($task->repetition) return true;
            //未完成
            if (!$userTask->achieve) return true;

            return false;

        })->all();

        $res['list'] = TaskResource::collection($list);

        return $this->response($res);
    }

}
