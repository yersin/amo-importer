<?php

namespace App\Http\Controllers\Admin;

use App\AmoConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AmoConfigController extends Controller
{
    public $amoConfig;

    public function __construct(AmoConfig $amoConfig)
    {
        $this->amoConfig = $amoConfig;
        $this->amoConfig->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
    }
    public function anyIndex()
    {
        $amo_configs = $this->amoConfig->all();
        return view("admin.amo.index", compact("amo_configs"));
    }

    public function getAdd()
    {
        return view("admin.amo.add");
    }

    public function postAdd(Request $request)
    {
        $this->amoConfig->create($request->all());
        $message = ["status"=>"success", "text" => "Новая Amo конфигурация успешно добавлена"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getEdit($id)
    {
        $amo_config = $this->amoConfig->find($id);
        return view("admin.amo.edit", compact("amo_config"));
    }

    public function postEdit($id, Request $request)
    {
        $this->amoConfig->find($id)->update($request->all());
        $message = ["status"=>"success", "text" => "Конфигурация amo успешно отредактирована"];
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

    public function getRemove($id)
    {
        $activity = $this->amoConfig->find($id);
        if($activity){
            $activity->delete();
            $message = ["status"=>"success", "text" => "Конфигурация amo успешно удалена"];
        }else{
            $message = ["status"=>"danger", "text" => "Не удалось удалить эту конфигурацию"];
        }
        return redirect("/admin/amo-configs")->with("message", (object)$message);
    }

}
