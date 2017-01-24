<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\FirmRubric;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function anyIndex()
    {
        $rubrics = FirmRubric::orderBy("groupTitle")->paginate(20);
        $acivities = Activity::pluck("name", "id");
        return view("admin.index", compact("rubrics", "acivities"));
    }

    public function postIndex(Request $request)
    {
        $rubric = FirmRubric::find($request->id);
        if(isset($request->need)){
            $need = $request->need == "true" ? FirmRubric::NEED : FirmRubric::NOT_NEED;
            if($rubric->notNeed != $need){
                $rubric->notNeed = $need;
                $rubric->save();
            }
        }else if($request->activity_id){
            $rubric->activity_id = $request->activity_id;
            $rubric->save();
        }
    }

    public function getRubricActivities()
    {
        $rubrics = FirmRubric::where("notNeed", FirmRubric::NEED)->orderBy("groupTitle")->paginate(20);
        $acivities = Activity::pluck("name", "id");
        return view("admin.set_rubric_activities", compact("rubrics", "acivities"));
    }



}
