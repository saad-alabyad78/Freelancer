<?php

namespace App\Http\Controllers\Project;

use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Requests\Project\UploadFilesRequest;
use App\Http\Requests\Project\UpdateProjectRequest;

class ProjectController extends Controller
{
    public function clientOk(Project $project)
    {
        $project->update(['client_ok' => true]) ;
    }
    public function update(Project $project , UpdateProjectRequest $request)
    {
        if($request->has('file_ids')){
            File::whereIn('id' , $request->input('file_ids' , []))
            ->update([
                'filable_id' => $project->id , 
                'filable_type' => Project::class ,
            ]) ;
        }

        return ProjectResource::make($project) ;
    }
    public function show(Project $project)
    {
        return ProjectResource::make($project);
    }
    public function reportToAdmin(Project $project)
    {
        //todo
    }
    public function delete(Project $project)
    {
        $project->delete() ;
        return response()->json(['message'=>'deleted']);
    }
}
