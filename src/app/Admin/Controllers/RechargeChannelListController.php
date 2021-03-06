<?php

namespace App\Admin\Controllers;

use App\Enums\RechargeChannelType;
use App\Models\RechargeChannelList;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class RechargeChannelListController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new RechargeChannelList(), function (Grid $grid) {

            $grid->model()->with(['rechargeChannel'])->where('recharge_channel_id', request('recharge_channel_id'))->orderBy('order', 'desc');

            $grid->column('id')->sortable();
            $grid->column('bank_code');
            $grid->column('bank_cover')->image('', 50, 50);
            $grid->column('bank_name');
            $grid->column('card_bank_name');
            $grid->column('card_number');
            $grid->column('card_user_name');
            $grid->column('max_money');
            $grid->column('min_money');
            $grid->column('name');
            $grid->column('order')->editable();
            $grid->column('rechargeChannel.name');
            $grid->column('status')->switch();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });

            $grid->disableCreateButton();
            $grid->disableDeleteButton();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new RechargeChannelList(), function (Show $show) {
            $show->field('id');
            $show->field('bank_code');
            $show->field('bank_cover');
            $show->field('bank_name');
            $show->field('card_bank_name');
            $show->field('card_number');
            $show->field('card_user_name');
            $show->field('max_money');
            $show->field('min_money');
            $show->field('name');
            $show->field('order');
            $show->field('recharge_channel_id');
            $show->field('son_bank_list');
            $show->field('status');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new RechargeChannelList(), function (Form $form) {
            $form->text('name')->required();
            if ($form->model()->type == RechargeChannelType::TransferAccounts) {
                $form->text('card_user_name', '???????????????')->required();
                $form->text('card_number', '???????????????')->required();
                $form->text('card_bank_name', '???????????????')->required();
            }

            if ($form->model()->type == RechargeChannelType::OnLine) {
                $form->image('bank_cover', '????????????')->autoUpload()->required()->width(2);
                $form->text('bank_name', '????????????')->required();
                $form->text('bank_code', '????????????')->required();
            }

            $form->number('order', '??????')->default(1)->required();
            $form->number('min_money', '????????????')->default(1)->required()->help('??????????????????????????????');
            $form->number('max_money', '????????????')->help('0????????????');

            $form->switch('status', '??????');

            $form->table('son_bank_list', '???????????????', function (Form\NestedForm $table) {
                $table->text('code', '????????????');
                $table->text('name', '????????????');
                $table->switch('status', '??????');

            });
        });
    }
}
