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
    public function anyIndex()
    {
        $rubrics = $this->firmRubric->orderBy("groupTitle")->paginate(20);
        $acivities = $this->act->pluck("name", "id");
        return view("admin.index", compact("rubrics", "acivities"));
    }

    public function postIndex(Request $request)
    {
        $rubric = $this->firmRubric->find($request->id);
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
        $rubrics = $this->firmRubric->where("notNeed", FirmRubric::NEED)->orderBy("groupTitle")->paginate(20);
        $acivities = $this->act->pluck("name", "id");
        return view("admin.set_rubric_activities", compact("rubrics", "acivities"));
    }

}
