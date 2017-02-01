<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function anyIndex(Activity $activity)
    {
        $activity->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activities = $activity->orderBy("name")->paginate(10);
        return view("admin.activities.index", compact("activities"));
    }

    public function getAdd()
    {
        return view("admin.activities.add");
    }

    public function postAdd(Request $request,Activity $activity)
    {
        $activity->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity->create($request->all());
        $message = ["status"=>"success", "text" => "Деятельность успешно добавлена"];
        return redirect("/admin/activities")->with("message", (object)$message);
    }

    public function getEdit($id, Activity $act)
    {
        $act->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity = $act->find($id);
        return view("admin.activities.edit", compact("activity"));
    }

    public function postEdit($id, Request $request, Activity $act)
    {
        $act->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $act->find($id)->update($request->all());
        $message = ["status"=>"success", "text" => "Деятельность успешно отредактирована"];
        return redirect("/admin/activities")->with("message", (object)$message);
    }

    public function getRemove($id, Activity $act)
    {
        $activity = $act->find($id);
        if($activity){
            $activity->delete();
            $message = ["status"=>"success", "text" => "Деятельность успешно удалена"];
        }else{
            $message = ["status"=>"danger", "text" => "Не удалось удалить эту деятельность"];
        }
        return redirect("/admin/activities")->with("message", (object)$message);
    }

}
