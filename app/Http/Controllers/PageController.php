<?php

namespace App\Http\Controllers;

use App\AmoConfig;
use App\Firm;
use App\Helpers\CRMHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    /*
     * FIELDS
     */
    public $amo_subdomain;
    public $amo_login;
    public $amo_hash;
    public $crm;
    public function __construct(CRMHelper $crm)
    {
        $this->crm = $crm;
        $this->amo_subdomain = env("AMO_SUBDOMAIN");
        $this->amo_login =  env("AMO_LOGIN");
        $this->amo_hash =  env("AMO_HASH");
    }

    /*
     * PAGES
     */
    public function anyIndex(Firm $firm)
    {
        $firm->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $integrated = $firm->where("isIntegrated", Firm::INTEGRATED)->count();
        $not_integrated = $firm->integrated()->get()->count();
        $amo_configs = AmoConfig::all()->pluck("name", "id");
        return view("pages.index", compact("integrated","not_integrated", "amo_configs" ));
    }

    public function anyAmo(Firm $firm)
    {
        $integrated = $firm->where("isIntegrated", Firm::INTEGRATED)->count();
        $not_integrated = $firm->integrated()->get()->count();
        return view("pages.index", compact("integrated","not_integrated" ));
    }

    public function postIndex(Request $request, Firm $firm)
    {
        $firm->setConnection(session("project") == "han" ? "mysql_han" : "mysql_mk");
        $amo_config = AmoConfig::find($request->amo_id);
        if(!$amo_config){
            return back()->with("message", (object)["status" => "danger", "text" => " Не удалось добавить компании" ]);
        }
        try {
            // Создание клиента
            $amo = new \AmoCRM\Client($amo_config->subdomain, $amo_config->login, $amo_config->hash);
            $total = $request->total ? $request->total: 5000;
            $chunks = $request->chunk ? $request->chunk : 200;
            $firms = $firm->integrated()->take($total)->get();
            $custom_fields = $amo->account->apiCurrent()["custom_fields"]["companies"];
            $company_fields = $this->crm->getCompanyFields($custom_fields);
            foreach (array_chunk($firms->all(), $chunks) as $key => $firm_rows){
                $companies = [];
                foreach ($firm_rows as $firm){
                    $company = $amo->company;
                    $company["name"] = $firm->title;
                    $company['responsible_user_id'] = 607140;
                    $company->addCustomField($company_fields["address"], $firm->address);
                    $company->addCustomField($company_fields["email"], $firm->email, "WORK");
                    $company->addCustomField($company_fields["filial"], $firm->filials);
                    $company->addCustomField($company_fields["phone"], $this->getPhonesArray($firm));
                    $company->addCustomField($company_fields["payment_type"], $firm->paymentMethod);
                    $company->addCustomField($company_fields["web"], $firm->links);
                    $company->addCustomMultiField($company_fields["category"], $this->getCategory($firm, $custom_fields));
                    $company->addCustomField($company_fields["sub_category"], $this->getSubCategory($firm));
                    $companies[] = $company;
                }
                $amo->company->apiAdd($companies);
                Firm::whereIn("id", array_pluck($firm_rows, "id"))
                    ->update(["isIntegrated" => Firm::INTEGRATED]);
            }

        } catch (\AmoCRM\Exception $e) {
            printf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
        }

        return back()->with("message", (object)["status" => "success", "text" => "Добавлено: " . $total]);
    }

    public function postSetProject($project)
    {
        if($project == "mk" || $project == "han"){
            Session::put("project", $project);
        }
    }

    /*
     * METHODS
     */
    public function getPhonesArray($firm)
    {
        $phones = [];
        foreach ($firm->phones as $phone){
            $enum = substr($phone->phoneNumber, 0, 3) == "7727" ? "HOME" : "MOB";
            $phones[] = [$phone->phoneNumber, $enum];
        }
        return $phones;
    }

    public function getCategory($firm, $custom_fields)
    {
        $category_enums = $this->crm->getCategoryEnums($custom_fields);
        $category_enums = array_flip($category_enums);
        $category = [];
        foreach($firm->category as $cat){
            if($cat->activity && $category_enums[$cat->activity->name]){
                $category[] = $category_enums[$cat->activity->name];
            }
        }
        return $category;
    }

    public function getSubCategory($firm)
    {
        $category = "";
        foreach($firm->category as $cat){
            $category .= $cat->groupTitle . ",";
        }
        return $category;
    }
}
