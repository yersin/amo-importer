<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function anyIndex()
    {
        $activities = Activity::orderBy("name")->paginate(10);
        return view("admin.activities.index", compact("activities"));
    }

    public function getAdd()
    {
        return view("admin.activities.add");
    }

    public function postAdd(Request $request)
    {
        Activity::create($request->all());
        $message = ["status"=>"success", "text" => "Деятельность успешно добавлена"];
        return redirect("/admin/activities")->with("message", (object)$message);
    }

    public function getEdit($id)
    {
        $activity = Activity::find($id);
        return view("admin.activities.edit", compact("activity"));
    }

    public function postEdit($id, Request $request)
    {
        Activity::find($id)->update($request->all());
        $message = ["status"=>"success", "text" => "Деятельность успешно отредактирована"];
        return redirect("/admin/activities")->with("message", (object)$message);
    }

    public function getRemove($id)
    {
        $activity = Activity::find($id);
        if($activity){
            $activity->delete();
            $message = ["status"=>"success", "text" => "Деятельность успешно удалена"];
        }else{
            $message = ["status"=>"danger", "text" => "Не удалось удалить эту деятельность"];
        }
        return redirect("/admin/activities")->with("message", (object)$message);
    }

}
