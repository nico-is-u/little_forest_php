<?php
/**
 * 小程序用户管理
 */
namespace app\backend\controller\App;

use app\common\controller\Backend;
use app\common\model\AppUsers;

use think\App;
use think\facade\View;
use think\facade\Request;

use app\common\annotation\ControllerAnnotation;
use app\common\annotation\NodeAnnotation;

use app\common\service\CosService;

/**
 * @ControllerAnnotation(title="小程序用户")
 * Class Users
 * @package app\backend\controller\App
 */
class Users extends Backend
{
    protected $allowModifyFields = ['status'];
    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->modelClass = new AppUsers();
    }

    /**
     * @NodeAnnotation(title="用户列表")
     * @return \think\response\View
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if ($this->request->param('selectFields')) {
                $this->selectList();
            }
            list($this->page, $this->pageSize, $sort, $where) = $this->buildParames();
            $list = $this->modelClass
                ->where($where)
                ->order($sort)
                ->paginate([
                    'list_rows' => $this->pageSize,
                    'page' => $this->page,
                ]);
            $result = ['code' => 0, 'msg' => lang('Get Data Success'), 'data' => $list->items(), 'count' => $list->total()];
            return json($result);
        }
        return view();
    }

    /**
     * @NodeAnnotation(title="添加用户")
     * @return \think\response\View
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $rule = [
                
            ];
            // 如果提供了openid，验证唯一性
            if (!empty($post['openid'])) {
                $rule['openid'] = 'unique:app_users';
            }
            if (!empty($rule)) {
                $this->validate($post, $rule);
            }
            $save = $this->modelClass->save($post);
            if ($save) {
                $this->success(lang('operation success'));
            } else {
                $this->error(lang('add fail'));
            }
        }
        $view = [
            'formData' => '',
            'title' => lang('Add'),
        ];
        View::assign($view);
        return view();
    }

    /**
     * @NodeAnnotation(title="编辑用户")
     * @return \think\response\View
     */
    public function edit()
    {
        $id = $this->request->get('id');
        if ($this->request->isPost()) {
            $list = $this->modelClass->find($id);
            empty($list) && $this->error(lang('Data is not exist'));
            $post = $this->request->post();
            $rule = [
            ];
            // 如果提供了openid，验证唯一性
            if (!empty($post['openid'])) {
                $rule['openid'] = 'unique:app_users';
            }
            if (!empty($rule)) {
                $this->validate($post, $rule);
            }
            $res = $list->save($post);
            if ($res) {
                $this->success(lang('operation success'), __u('index'));
            } else {
                $this->error(lang('Edit fail'));
            }
        }
        $list = AppUsers::find(Request::get('id'));
        $view = [
            'formData' => $list,
            'title' => lang('Edit'),
        ];
        View::assign($view);
        return view('add');
    }

    /**
     * @NodeAnnotation(title="删除用户")
     * @return mixed
     */
    public function delete()
    {
        $ids = $this->request->param('ids') ? $this->request->param('ids') : $this->request->param('id');
        $list = $this->modelClass->find($ids);
        $res = $list->force(true)->delete();
        $res ? $this->success(lang('operation success')) : $this->error(lang('delete failed'));
    }

    /**
     * @NodeAnnotation(title="上传头像")
     * @return mixed
     */
    public function uploadAvatar(CosService $cosService)
    {

        $cosFileName = 'bd53aa611fa4c95a56d59d9f25f35ec5.jpg';
        $result = $cosService->upload('storage/upload/20260123/bd53aa611fa4c95a56d59d9f25f35ec5.jpg', $cosFileName);
    
        if ($result['code'] != 1) {
            die("❌ 上传失败：" . $result['msg'] . "\n\n");
        }else{
            echo "✅ 上传成功\n";
            echo "   文件名：{$cosFileName}\n";
            dump($result);
            // echo "   COS路径：" . $result['data']['path'] . "\n\n";
        }
        

    }
}
