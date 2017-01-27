<?php

namespace App\Http\Controllers\Admin;

use App\AmoConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AmoConfigController extends Controller
{
    public function anyIndex()
    {
        $amo_configs = AmoConfig::all();
        return view("admin.amo.index", compact("amo_configs"));
    }

    public function getAdd()
    {
        return view("admin.amo.add");
    }

    public function postAdd(Request $request)
    {
        AmoConfig::create($request->all());
        $message = ["status"=>"success", "text" => "Новая Amo конфигурация успешно добавлена"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getEdit($id)
    {
        $amo_config = AmoConfig::find($id);
        return view("admin.amo.edit", compact("amo_config"));
    }

    public function postEdit($id, Request $request)
    {
        AmoConfig::find($id)->update($request->all());
        $message = ["status"=>"success", "text" => "Конфигурация amo успешно отредактирована"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getRemove($id)
    {
        $activity = AmoConfig::find($id);
        if($activity){
            $activity->delete();
            $message = ["status"=>"success", "text" => "Конфигурация amo успешно удалена"];
        }else{
            $message = ["status"=>"danger", "text" => "Не удалось удалить эту конфигурацию"];
        }
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

}
