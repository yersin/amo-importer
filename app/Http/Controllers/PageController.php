<?php

namespace App\Http\Controllers;

use App\Firm;
use App\Helpers\CRMHelper;
use Illuminate\Http\Request;

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
        $integrated = $firm->where("isIntegrated", Firm::INTEGRATED)->count();
        $not_integrated = $firm->integrated()->get()->count();
        return view("pages.index", compact("integrated","not_integrated" ));
    }

    public function anyAmo(Firm $firm)
    {
        $integrated = $firm->where("isIntegrated", Firm::INTEGRATED)->count();
        $not_integrated = $firm->integrated()->get()->count();
        return view("pages.index", compact("integrated","not_integrated" ));
    }

    public function postIndex(Request $request, Firm $firm)
    {
        try {
            // Создание клиента
            $amo = new \AmoCRM\Client($this->amo_subdomain, $this->amo_login, $this->amo_hash);
            $total = $request->total ? $request->total: 5000;
            $chunks = $request->chunk ? $request->chunk : 200;
            $firms = $firm->integrated()->take($total)->get();
            $company_fields = $this->crm->getCompanyFields($amo->account->apiCurrent()["custom_fields"]["companies"]);
            foreach (array_chunk($firms->all(), $chunks) as $key => $firm_rows){
                $companies = [];
                foreach ($firm_rows as $firm){
                    $company = $amo->company;
                    $company["name"] = $firm->title;
                    $company->addCustomField($company_fields["address"], $firm->address);
                    $company->addCustomField($company_fields["email"], $firm->email, "WORK");
                    $company->addCustomField($company_fields["filial"], $firm->filials);
                    $company->addCustomField($company_fields["phone"], $this->getPhonesArray($firm));
                    $company->addCustomField($company_fields["payment_type"], $firm->paymentMethod);
                    $company->addCustomField($company_fields["web"], $firm->links);
                    $company->addCustomField($company_fields["info"], $firm->info);
                    $company->addCustomMultiField($company_fields["category"], $this->getCategory($firm));
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

    public function getCategory($firm)
    {
        $category = [];
        foreach($firm->category as $cat){
            if($cat->activity){
                $category[] = $this->crm->enums[$cat->activity->name];
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
