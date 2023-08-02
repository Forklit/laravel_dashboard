<?php

namespace App\Admin\Controllers;

use App\Models\ArticleType;
use App\Models\Article;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Article';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article());

        $grid->column('title', "Title");
        $grid->column('sub_title', __("Sub Title"));
        $grid->column('article.title', __('Category'));
        $grid->column('released')->bool();
        $grid->column('description', __('Content'))->display(function($val){
           return substr($val, 0, 300); 
        });
        
        $grid->column('thumbnail', __('Thumbnail'))->image('','100', '100')->display(function($val){
            if(empty($val)){
                return "No Thumbnal";
            }
            return $val;
        });
        $grid->model()->orderBy('created_at','desc');
        $grid->filter(function($filter){
           $filter->disableIdFilter();
           $filter->like('title', __('Title'));
           $filter->like('article.title', __('Category'));
        });

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
        $show = new Show(Article::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article());
        $form->select('type_id', __("Category"))->options((new ArticleType())::selectOptions());
        $form->text('title',__('Title'));
        $form->text('sub_title',__('Sub Title'));
        $form->image('thumbnail')->move('/storage');
        $form->text('description',__('Content'));
       
        $states = [
            'on'=>['value'=>1, 'text'=>'publish', 'color'=>'success'],
            'off'=>['value'=>0, 'text'=>'draft', 'color'=>'default'],
            ];
            
        $form->switch('released', __('Published'))->states($states);


        return $form;
        }
        
}
