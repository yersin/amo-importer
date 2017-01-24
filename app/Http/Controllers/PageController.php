<?php

namespace App\Http\Controllers;

use App\Helpers\CRMHelper;
use Illuminate\Http\Request;

class PageController extends Controller
{
    const SUBDOMAIN = 'new5880aa87e5ccd';
    public $crm;
    public function __construct(CRMHelper $crm)
    {
        $this->crm = $crm;
    }
    public function anyIndex()
    {
        return view("pages.index");
    }

    public function postIndex(Request $request)
    {
//        dd($this->crm->getCurrentAccount()["custom_fields"]);
        if($this->crm->auth()){
            $this->crm->add($request->all());
        }
    }

}
