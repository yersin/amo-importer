<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Firm;
use App\FirmRubric;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public $firmRubric;
    public $act;

    public function __construct(FirmRubric $firmRubric, Activity $act)
    {
        $this->firmRubric = $firmRubric;
        $this->act = $act;
        $this->firmRubric->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $this->act->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
    }
    public function anyIndex(FirmRubric $firmRubric, Activity $activity)
    {
        $firmRubric->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $rubrics = $firmRubric->orderBy("groupTitle")->paginate(20);
        $activities = $activity->pluck("name", "id");
        return view("admin.index", compact("rubrics", "activities"));
    }

    public function postIndex(Request $request, FirmRubric $firmRubric)
    {
        $firmRubric->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $rubric = $firmRubric->find($request->id);
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

    public function getRubricActivities(FirmRubric $firmRubric, Activity $activity)
    {
        $firmRubric->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $rubrics = $firmRubric->where("notNeed", FirmRubric::NEED)->orderBy("groupTitle")->paginate(20);
        $activities = $activity->pluck("name", "id");
        return view("admin.set_rubric_activities", compact("rubrics", "activities"));
    }

    /*
     * AJAX
     */
    public function getRubricSearch($search, Activity $activity, FirmRubric $firmRubric, Request $request)
    {
        $firmRubric->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $rubrics = $firmRubric->where("title", "LIKE", "%".$search."%")
                             ->orWhere("groupTitle", "LIKE", "%".$search."%")->get();
        $activities = $activity->pluck("name", "id");
        $type = $request->type;
        return view("admin.inc.rubric_search_table", compact("rubrics", "activities", "type"));
    }
}
