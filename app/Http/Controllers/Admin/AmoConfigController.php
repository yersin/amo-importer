<?php

namespace App\Http\Controllers\Admin;

use App\AmoConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AmoConfigController extends Controller
{

    public function anyIndex(AmoConfig $amoConfig)
    {
        $amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $amo_configs = $amoConfig->all();
        return view("admin.amo.index", compact("amo_configs"));
    }

    public function getAdd()
    {
        return view("admin.amo.add");
    }

    public function postAdd(Request $request, AmoConfig $amoConfig)
    {
        $amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $amoConfig->create($request->all());
        $message = ["status"=>"success", "text" => "Новая Amo конфигурация успешно добавлена"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getEdit($id, AmoConfig $amoConfig)
    {
        $amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $amo_config = $amoConfig->find($id);
        return view("admin.amo.edit", compact("amo_config"));
    }

    public function postEdit($id, Request $request, AmoConfig $amoConfig)
    {
        $amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $amoConfig->find($id)->update($request->all());
        $message = ["status"=>"success", "text" => "Конфигурация amo успешно отредактирована"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getRemove($id, AmoConfig $amoConfig)
    {
        $amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $activity = $amoConfig->find($id);
        if($activity){
            $activity->delete();
            $message = ["status"=>"success", "text" => "Конфигурация amo успешно удалена"];
        }else{
            $message = ["status"=>"danger", "text" => "Не удалось удалить эту конфигурацию"];
        }
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

}
