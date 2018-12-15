<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Reply;
use App\Models\User;
use App\Models\Work;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('控制台')
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('查看所有待审核项目', 'file-pdf-o', 'aqua', '/admin/projects?&_scope_=reviewing', '审核项目');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('收到甲方汇款后，请在此登记入账信息', 'bitcoin', 'aqua', '/admin/project_remittances', "项目汇款入账登记");
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('项目结束后，向设计师发放设计费', 'dollar', 'aqua', '/admin/project_payments', "设计费发放登记");
                    $column->append($infoBox);
                });
            })
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(User::where('type', 'designer')->count() . ' 名', 'users', 'light-blue', '/admin/users?&_scope_=designer', '设计师');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(User::where('type', 'client')->count(). ' 名', 'users', 'light-blue', '/admin/users?&_scope_=client', '甲方');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(Project::count(). ' 个', 'file-pdf-o', 'light-blue', '/admin/projects', '项目');
                    $column->append($infoBox);
                });
            })
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(Work::count(). ' 个', 'picture-o', 'light-blue', '/admin/works', '作品集');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(Activity::count(). ' 条', 'paper-plane', 'light-blue', '/admin/activities', '动态');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox(Reply::count(). ' 条', 'comments', 'light-blue', '/admin/replies', '评论');
                    $column->append($infoBox);
                });
            });
    }
}
