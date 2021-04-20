<?php

namespace App\Admin\Controllers;

use App\Models\CandleStick;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CandleStickController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CandleStick';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CandleStick());

        $grid->column('id', __('Id'));
        $grid->column('crypt_type', __('Crypt type'));
        $grid->column('interval', __('Interval'));
        $grid->column('open_time', __('Open time'));
        $grid->column('line_direction', __('Line direction'));
        $grid->column('open_price', __('Open price'));
        $grid->column('close_price', __('Close price'));
        $grid->column('high_price', __('High price'));
        $grid->column('low_price', __('Low price'));
        $grid->column('volume', __('Volume'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CandleStick::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('crypt_type', __('Crypt type'));
        $show->field('interval', __('Interval'));
        $show->field('open_time', __('Open time'));
        $show->field('line_direction', __('Line direction'));
        $show->field('open_price', __('Open price'));
        $show->field('close_price', __('Close price'));
        $show->field('high_price', __('High price'));
        $show->field('low_price', __('Low price'));
        $show->field('volume', __('Volume'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CandleStick());

        $form->text('crypt_type', __('Crypt type'));
        $form->text('interval', __('Interval'));
        $form->datetime('open_time', __('Open time'))->default(date('Y-m-d H:i:s'));
        $form->switch('line_direction', __('Line direction'));
        $form->decimal('open_price', __('Open price'));
        $form->decimal('close_price', __('Close price'));
        $form->decimal('high_price', __('High price'));
        $form->decimal('low_price', __('Low price'));
        $form->decimal('volume', __('Volume'));

        return $form;
    }
}
