<?php

namespace App\Admin\Controllers;

use App\Models\CouponCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponCodesController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return Admin::content(function (Content $content) {
            $content->header('优惠券列表');
            $content->body($this->grid());
        });
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return Admin::content(function (Content $content) {
            $content->header('新增优惠券');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(CouponCode::class, function (Grid $grid) {
            $grid->model()->orderBy('created_at', 'desc');
            $grid->id('ID')->sortable();
            $grid->name('名称');
            $grid->code('优惠码');
            $grid->description('描述');
            $grid->column('usage', '用量')->display(function ($value) {
                return "{$this->used} / {$this->total}";
            });
            $grid->enabled('是否启用')->display(function ($value) {
                return $value ? '是' : '否';
            });
            $grid->created_at('创建时间');
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CouponCode::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->code('Code');
        $show->type('Type');
        $show->value('Value');
        $show->total('Total');
        $show->used('Used');
        $show->min_amount('Min amount');
        $show->not_before('Not before');
        $show->not_after('Not after');
        $show->enabled('Enabled');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(CouponCode::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', '名称')->rules('required');
            $form->text('code', '优惠码')->rules('nullable|unique:coupon_codes');
            $form->radio('type', '类型')->options(CouponCode::$typeMap)->rules('required');
            $form->text('value', '折扣')->rules(function ($form) {
                if ($form->type === CouponCode::TYPE_PERCENT) {
                    // 如果选择了百分比折扣类型，那么折扣范围只能是 1 ~ 99
                    return 'required|numeric|between:1,99';
                } else {
                    // 否则只要大等于 0.01 即可
                    return 'required|numeric|min:0.01';
                }
            });
            $form->text('total', '总量')->rules('required|numeric|min:0');
            $form->text('min_amount', '最低金额')->rules('required|numeric|min:0');
            $form->datetime('not_before', '开始时间');
            $form->datetime('not_after', '结束时间');
            $form->radio('enabled', '启用')->options(['1' => '是', '0' => '否']);

            $form->saving(function (Form $form) {
                if (!$form->code) {
                    $form->code = CouponCode::findAvailableCode();
                }
            });
        });
    }
}
