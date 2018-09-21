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
                $row->column(6, function (Column $column) {
                    $infoBox = new InfoBox('收到甲方汇款后，请在此登记入账信息', 'bitcoin', 'aqua', '/admin/project_remittances', "项目汇款入账登记");
                    $column->append($infoBox);
                });
                $row->column(6, function (Column $column) {
                    $infoBox = new InfoBox('项目结束后，请按照甲方申请向设计师发放设计费', 'dollar', 'aqua', '/admin/project_payments', "设计费发放登记");
                    $column->append($infoBox);
                });
            })
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('设计师', 'users', 'light-blue', '/admin/users', User::where('type', 'designer')->count() . ' 名');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('甲方', 'users', 'light-blue', '/admin/users', User::where('type', 'party')->count(). ' 名');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('项目', 'file-pdf-o', 'light-blue', '/admin/projects', Project::count(). ' 个');
                    $column->append($infoBox);
                });
            })
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('作品集', 'picture-o', 'light-blue', '/admin/works', Work::count(). ' 个');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('动态', 'paper-plane', 'light-blue', '/admin/activities', Activity::count(). ' 条');
                    $column->append($infoBox);
                });
                $row->column(4, function (Column $column) {
                    $infoBox = new InfoBox('评论', 'comments', 'light-blue', '/admin/replies', Reply::count(). ' 条');
                    $column->append($infoBox);
                });
            });
    }
}
