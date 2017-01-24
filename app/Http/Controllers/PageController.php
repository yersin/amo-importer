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
    public $crm;
    public function __construct(CRMHelper $crm)
    {
        $this->crm = $crm;
    }

    /*
     * PAGES
     */
    public function anyIndex()
    {
        $integrated = Firm::where("is_integrated", Firm::INTEGRATED)->count();
        $not_integrated = Firm::where("is_integrated", Firm::NOT_INTEGRATED)->count();
        return view("pages.index", compact("integrated","not_integrated" ));
    }

    public function postIndex(Request $request)
    {
        try {
            // Создание клиента
            $amo = new \AmoCRM\Client(CRMHelper::SUBDOMAIN, CRMHelper::USER_LOGIN, CRMHelper::USER_HASH);
            $total = $request->total ? $request->total: 5000;
            $chunks = $request->chunk ? $request->chunk : 150;
            $firms = Firm::where("isIntegrated", Firm::NOT_INTEGRATED)->take($total)->get();
            dd(Firm::where("isIntegrated", Firm::INTEGRATED)->get());
            $companies = [];
            foreach (array_chunk($firms->all(), $chunks) as $key => $firm_rows){
                foreach ($firm_rows as $firm){
                    $company = $amo->company;
                    $company["name"] = $firm->title;
                    $company->addCustomField($this->crm->company["address"], $firm->address);
                    $company->addCustomField($this->crm->company["email"], $firm->email, "WORK");
                    $company->addCustomField($this->crm->company["filial"], $firm->filials);
                    $company->addCustomField($this->crm->company["phone"], $this->getPhonesArray($firm));
                    $company->addCustomField($this->crm->company["payment_type"], $firm->paymentMethod);
                    $company->addCustomField($this->crm->company["web"], $firm->links);
                    $company->addCustomField($this->crm->company["info"], $firm->info);
                    $company->addCustomField($this->crm->company["category"], $firm->info);
                    $companies[] = $company;
                }
                $amo->company->apiAdd($companies);
                Firm::whereIn("id", array_pluck($firm_rows, "id"))
                    ->update(["is_integrated" => Firm::INTEGRATED]);
            }

        } catch (\AmoCRM\Exception $e) {
            printf('Error (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage());
        }

        return back()->with();
    }


    /*
     * METHODS
     */
    public function getPhonesArray($firm)
    {
        $phones = [];
        foreach ($firm->phones as $phone){
            $enum = substr($phone->phoneNumber, 0, 3) == "7727" ? "HOME" : "MOB";
            $phones[] = [$phone->phoneNumber, $this->crm->enums[$enum]];
        }
        return $phones;
    }



}
