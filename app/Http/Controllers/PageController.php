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
        $firms = Firm::where("is_integrated", Firm::NOT_INTEGRATED)->take(10000)->get();
        foreach (array_chunk($firms->all(), 500) as $key => $firm_rows){
            foreach ($firm_rows as $firm){
                $request = ["name" => $firm->title];
                $request["custom_fields"] = [
                    [
                        'id'=> $this->crm->company['email'],
                        'values'=> [
                            [
                                'value'=> $firm->email,
                                'enum'=>'WORK'
                            ]
                        ]
                    ],
                    [
                        'id'=> $this->crm->company['phone'],
                        'values'=> $this->getPhonesArray($firm)
                    ],
                    [
                        'id'=> $this->crm->company['web'],
                        'values'=> [
                            [
                                'value'=> $firm->links,
                            ]
                        ],
                    ],
                    [
                        'id'=> $this->crm->company['address'],
                        'values'=> [
                            [
                                'value'=> $firm->address,
                            ]
                        ]
                    ],
                    [
                        'id'=> $this->crm->company['info'],
                        'values'=> [
                            [
                                'value'=> $firm->info,
                            ]
                        ]
                    ],
                    [
                        'id'=> $this->crm->company['payment_type'],
                        'values'=> [
                            [
                                'value'=> $firm->paymentMethods,
                            ]
                        ]
                    ],
                    [
                        'id'=> $this->crm->company['filial'],
                        'values'=> [
                            [
                                'value'=> $firm->filials,
                            ]
                        ]
                    ]
                ];
                $set['request']['contacts']['add'][] = $request;
                dd($set);
            }
            if($this->crm->auth()){
                $this->crm->add($set);
            }

            Firm::whereIn("id", array_pluck($firm_rows, "id"))
                ->update(["is_integrated" => Firm::INTEGRATED]);
        }
    }

    public function getAmo()
    {
        try {
            // Создание клиента
            $amo = new \AmoCRM\Client(CRMHelper::SUBDOMAIN, CRMHelper::USER_LOGIN, CRMHelper::USER_HASH);

            $firms = Firm::where("isIntegrated", Firm::NOT_INTEGRATED)->take(10000)->get();
            dd(Firm::where("isIntegrated", Firm::INTEGRATED)->get());
            $companies = [];
            foreach (array_chunk($firms->all(), 500) as $key => $firm_rows){
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
    }

    public function postIndex(Request $request)
    {
//        dd($this->crm->getCurrentAccount()["custom_fields"]);
        if($this->crm->auth()){
            $this->crm->add($request->all());
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
            $phones[] = [$phone->phoneNumber, $this->crm->enums[$enum]];
        }
        return $phones;
    }



}
