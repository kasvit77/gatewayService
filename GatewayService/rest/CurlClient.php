<?php
/**
 * Методы обработки Curl-запроса.
 */
class CurlClient
{
    /**
     * Объект Curl-запроса.
     */
    private $request;
    /**
     * Конструктор класса CURL.
     *
     * @throws Exception, если при инициализации возникли ошибки.
     */
    public function __construct() {
        $this->request = curl_init();
        $this->throwExceptionIfError($this->request);
    }
    /**
     * Настройка Curl -запроса.
     *
     * @param $url Целевой url-адрес.
     * @param $urlParameters Массив параметров в формате 'key' => 'value'.
     * @param $method 'GET' или 'POST'; по умолчанию - 'GET'.
     * @param $moreOptions Другие параметры, добавляемые к Curl -запросу.
     * По умолчанию задано 'CURLOPT_FOLLOWLOCATION'(переходить по редиректам 3XX) и 'CURLOPT_RETURNTRANSFER'
     * (возвращает ответ HTTP в качестве значения, вместо вывода напрямую).
     * @throws Exception, если возникли ошибки при настройке.
     * curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('OSDI-API-Token: xxxxxxxxxx'));
    $api_request_parameters = array('filter'=>"family_name eq 'Doe'");
    $api_request_url = "https://myapi.org/endpoint";
    $api_request_url .= "?".http_build_query($api_request_parameters);
    $curl_setopt($ch, CURLOPT_URL, $api_request_url);
     */
    public function configure($url, $urlParameters = [], $method = 'GET',$jsonType=false,
                              $moreOptions = [CURLOPT_HEADER=>false,
                                  CURLOPT_RETURNTRANSFER =>true,
                                  CURLOPT_SSL_VERIFYPEER =>false,
                                  CURLOPT_CONNECTTIMEOUT=>15,
                                  CURLOPT_FOLLOWLOCATION=> false,
                                  CURLOPT_TIMEOUT=>100,
                                  CURLOPT_USERAGENT =>'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 (.NET CLR 3.5.30729)',
                              ])


    {


        curl_reset($this->request);
        switch ($method) {
            case 'GET':
                $options = [CURLOPT_URL => $url . $this->stringifyParameters($urlParameters)];
                //  print_r($options);
                break;
            case 'POST':
                if($jsonType===true){
                    $options= [
                        CURLOPT_HTTPHEADER=> array("Content-Type:application/json;" ,"cache-control: no-cache;","Accept:application/json"),
                        CURLOPT_SSL_VERIFYPEER =>false,
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_USERAGENT =>'Fiddler',
                        CURLOPT_RETURNTRANSFER=>true,
                        CURLOPT_CONNECTTIMEOUT=>15,
                        CURLOPT_FOLLOWLOCATION=>true,
                        CURLOPT_POSTFIELDS =>json_encode($urlParameters)
                    ];

                }else{
                    $options=[
                        CURLOPT_SSL_VERIFYPEER =>false,
                        CURLOPT_URL => $url,
                        CURLOPT_POST => true,
                        CURLOPT_CONNECTTIMEOUT=>15,
                        CURLOPT_TIMEOUT=>100,
                        CURLOPT_POSTFIELDS =>http_build_query($urlParameters)
                    ];
                }
                break;
            case 'PATCH':
                $options = [
                    CURLOPT_HTTPHEADER=> array("Content-Type: application/json-patch+json" ,"cache-control: no-cache;","Accept:application/json"),
                    CURLOPT_SSL_VERIFYPEER =>false,
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER=>true,
                    CURLOPT_CUSTOMREQUEST=>'PATCH',
                    CURLOPT_CONNECTTIMEOUT=>15,
                    CURLOPT_TIMEOUT=>100,
                    CURLOPT_POSTFIELDS =>json_encode($urlParameters)

                ];
                break;
            default:
                throw new Exception('Method must be "GET" or "POST".');
                break;
        }
        //   print_r($url);
        //  print_r($urlParameters);
        $options = $options + $moreOptions;
        foreach ($options as $option => $value) {
            $configured = curl_setopt($this->request, $option, $value);
            $this->throwExceptionIfError($configured);
        }
    }
    /**
     * Выполняем Curl-запрос в соответствии с параметрами конфигурации.
     *
     * @return возвращает значение функции curl_exec(). Если настроен CURLOPT_RETURNTRANSFER,
     *     возвращаемое значение будет ответом HTTP. В противном случае, значение true (или false,
     *     если возникла ошибка).
     * @throws Exception, если возникла ошибка при исполнении.
     */
    public function execute() {
        $result = curl_exec($this->request);
        $this->throwExceptionIfError($result);
        return $result;
    }
    /**
     * Закрываем сессию Curl.
     */
    public function close() {
        curl_close($this->request);
    }
    /**
     * Проверяем, вернули ли функции curl_* штатное значение или ошибку, добавляя исключение
     * с сообщением об ошибке Curl в случае возникновения ошибки.
     *
     * @param $success была ли функция curl выполнена успешно или нет.
     * @throws Exception, если функция curl не выполнена.
     */
    protected function throwExceptionIfError($success) {
        if (!$success) {
            throw new Exception(curl_error($this->request));
        }
    }
    /**
     * Составляем строку параметров GET.
     *
     * @param $parameters массив параметров.
     * @return Parameters в формате строки: '?key1=value1&key2=value2'
     */
    protected function stringifyParameters($parameters) {
        if($parameters) {
            $parameterString = '?';
            foreach ($parameters as $key => $value) {
                $key = urlencode($key);
                $value = urlencode($value);
                $parameterString .= "$key=$value&";
            }

            return rtrim($parameterString, '&');

        }
    }
}