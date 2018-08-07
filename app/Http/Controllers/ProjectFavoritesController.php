<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFavorite;
use Illuminate\Http\Request;

class ProjectFavoritesController extends Controller
{
    // 收藏
    public function store(Project $project, ProjectFavorite $projectFavorite)
    {
        $this->authorize('favorite', $project);
        $currentUser = $this->user();
        if (!ProjectFavorite::where([
            'project_id' => $project->id,
            'user_id'    => $currentUser->id
        ])->exists()) {
            $projectFavorite->user_id = $currentUser->id;
            $projectFavorite->project_id = $project->id;
            $projectFavorite->save();
            $project->increment('favorite_count');
        }
        return $this->response->noContent();
    }

    // 取消收藏
    public function destroy(Project $project)
    {
        $currentUser = $this->user();
        if (ProjectFavorite::where([
            'project_id' => $project->id,
            'user_id'    => $currentUser->id
        ])->exists()) {
            ProjectFavorite::where([
                'project_id' => $project->id,
                'user_id'    => $currentUser->id
            ])->delete();
            $project->decrement('favorite_count');
        }
        return $this->response->noContent();
    }
}
