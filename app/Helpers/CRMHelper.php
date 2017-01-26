<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 11:08
 */
namespace App\Helpers;

class CRMHelper
{
    /*
     * Information for connection
     */
    const SUBDOMAIN = "new5880aa87e5ccd";
    const USER_LOGIN = "k2960064@mvrht.com";
    const USER_HASH = "c248bcf86a67265d1c1243ae44bbb6b5";

    /*
     * FIELDS
     */
    public $company = [
        "name" => "NAME",
    ];
    public $company_names = [
        "phone" => "Телефон",
        "email" => "Email",
        "web" => "Web",
        "address" => "Адрес",
        "category" => "Категория",
        "sub_category" => "Подкатегория",
        "info" => "Информация",
        "payment_type" => "Вид оплаты",
        "filial" => "Филиалы",
    ];

    public $enums = [
       "Еда" => 4409810,
       "Здоровье" => 4409812,
       "Красота" => 4409814,
       "Развлечения и отдых" => 4409816,
       "Товары" => 4409818,
       "Услуги" => 4409820
    ];


    public $cookie_path;
    public function __construct()
    {
        $this->cookie_path = storage_path('framework/cookie/cookie.txt');
    }

    public function auth()
    {
        #Массив с параметрами, которые нужно передать методом POST к API системы
        $user=array(
            'USER_LOGIN' => self::USER_LOGIN, #Ваш логин (электронная почта)
            'USER_HASH' => self::USER_HASH #Хэш для доступа к API (смотрите в профиле пользователя)
        );

        #Формируем ссылку для запроса
        $link='https://'.self::SUBDOMAIN.'.amocrm.ru/private/api/auth.php?type=json';
        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
        #Устанавливаем необходимые опции для сеанса cURL
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_POST,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
        curl_setopt($curl,CURLOPT_COOKIEFILE, $this->cookie_path);
        curl_setopt($curl,CURLOPT_COOKIEJAR, $this->cookie_path);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
        curl_close($curl); #Заверашем сеанс cURL
        self::CheckCurlResponse($code);
        $Response=json_decode($out,true);
        $Response = $Response['response'];
        return isset($Response['auth']);
    }

    public function getCurrentAccount()
    {
        $link='https://'.self::SUBDOMAIN.'.amocrm.ru/private/api/v2/json/accounts/current'; #$subdomain уже объявляли выше
        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
        #Устанавливаем необходимые опции для сеанса cURL
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_COOKIEFILE,$this->cookie_path);
        curl_setopt($curl,CURLOPT_COOKIEJAR,$this->cookie_path);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        curl_close($curl);
        self::CheckCurlResponse($code);
        $Response=json_decode($out,true);
        $account = $Response['response']['account'];
        return $account;
    }

    public function isExist($data, $type = "contacts")
    {
        $link='https://'.self::SUBDOMAIN.'.amocrm.ru/private/api/v2/json/' .$type. '/list?query='.$data;
        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
        #Устанавливаем необходимые опции для сеанса cURL
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

        $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
        self::CheckCurlResponse($code);
        curl_close($curl);
        return $out != null;
    }

    public function add($data, $type = "company"){

        $link='https://'.self::SUBDOMAIN.'.amocrm.ru/private/api/v2/json/' .$type. '/set';
        $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
        curl_setopt($curl,CURLOPT_URL,$link);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURLOPT_COOKIEFILE, $this->cookie_path);
        curl_setopt($curl,CURLOPT_COOKIEJAR, $this->cookie_path);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
        $out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl,CURLINFO_HTTP_CODE);
        self::CheckCurlResponse($code);
        $Response=json_decode($out,true);
        $Response = $Response['response']['contacts']['add'];

        $output='ID добавленных контактов:'.PHP_EOL;
        foreach($Response as $v)
            if(is_array($v))
                $output.=$v['id'].PHP_EOL;
        return $output;
    }

    function CheckCurlResponse($code)
    {
        $code=(int)$code;
        $errors=array(
            301=>'Moved permanently',
            400=>'Bad request',
            401=>'Unauthorized',
            403=>'Forbidden',
            404=>'Not found',
            500=>'Internal server error',
            502=>'Bad gateway',
            503=>'Service unavailable'
        );
        try
        {
            #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
            if($code!=200 && $code!=204)
                throw new \Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
        }
        catch(Exception $E)
        {
            die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
        }
    }

    public function getCompanyFields($company_fields){
        foreach ($company_fields as $company_field){
            foreach (array_keys($this->company_names) as $key){
                if($this->company_names[$key] == $company_field["name"]){
                    $this->company[$key] = $company_field["id"];
                    break;
                }
            }
        }
        return $this->company;
    }

}