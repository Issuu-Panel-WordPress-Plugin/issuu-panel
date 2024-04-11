<?php

/**
*   Classe IssuuServiceAPI
*
*   @author Pedro Marcelo de Sá Alves
*   @link https://github.com/pedromarcelojava/
*   @version 1.2
*/
abstract class IssuuServiceAPI
{

    /**
    *   Token Bearer da API
    *
    *   @access private
    *   @var string
    */
    private $api_bearer_token;

    /**
    *   URL da API do Issuu
    *
    *   @access private
    *   @var string
    */
    private $api_url = 'https://api.issuu.com/v2';

    /**
    *   URL de upload do Issuu
    *
    *   @access private
    *   @var string
    */
    private $upload_url = 'https://api.issuu.com/v2/drafts/{slug}/upload';

    /**
    *   Parâmetros da requisição em forma de array
    *
    *   @access protected
    *   @var array
    */
    protected $params;

    /**
     * Header da requisição
     * @var array
     */
    protected $headers = array();

    /**
    *   Parâmetros da requisição em forma de string
    *
    *   @access protected
    *   @var string
    */
    protected $params_str;

    /**
    *   Nome do método list
    *
    *   @access protected
    *   @var string
    */
    protected $list;

    /**
    *   Nome do método delete
    *
    *   @access protected
    *   @var string
    */
    protected $delete;

    /**
    *   Slug da seção
    *
    *   @access protected
    *   @var string
    */
    protected $slug_section;

    /**
    *   IssuuServiceAPI::__construct()
    *
    *   Construtor da classe
    *
    *   @access public
    *   @param string $api_bearer_token Correspondente ao token Bearer da API
    *   @throws Exception Lança uma exceção caso não seja informada o token Bearer da API
    */
    public function __construct($api_bearer_token)
    {
        if (is_string($api_bearer_token) && strlen($api_bearer_token) >= 1)
        {
            $this->api_bearer_token = $api_bearer_token;
        }
        else
        {
            throw new Exception('O token Bearer da API não foi informado');
        }
    }

    /**
    *   IssuuServiceAPI::__destruct()
    *
    *   Desconstrutor da classe
    *
    *   @access public
    */
    public function __destruct()
    {
        return false;
    }

    /**
    *   IssuuServiceAPI::buildUrl()
    *
    *   Monta a URL da requisição
    *
    *   @access protected
    *   @param boolean $regular_request
    *   @param string $slug
    *   @return string Retorna a URL da api ou upload junto com os parâmetros passados
    */
    protected function buildUrl($regular_request = true, $slug = null)
    {
        if ($regular_request == true)
        {
            return $this->api_url . '?' . $this->params_str;
        }
        else if ($regular_request == false)
        {
            // override upload_url {slug} with $slug
            return str_replace('{slug}', $slug, $this->upload_url) . '?' . $this->params_str;
        }
        else
        {
            return false;
        }
    }

    /**
    *   IssuuServiceAPI::setParams()
    *
    *   Seta os parâmetros da requisição
    *
    *   @access public
    *   @param array $params
    *   @throws Exception Lança um exceção caso não tenha parâmetros
    */
    public function setParams($params)
    {
        if (is_array($params) && !empty($params))
        {
            $this->params = $params;
            $this->headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->api_bearer_token
            );
        }
        else
        {
            throw new Exception('Os parâmetros não é um array ou está vazio');
        }
    }

    /**
    *   IssuuServiceAPI::getParams()
    *
    *   Retorna os parâmetros da requisição
    *
    *   @access public
    *   @return array
    */
    public function getParams()
    {
        return $this->params;
    }

    /**
    *   IssuuServiceAPI::curlRequest()
    *
    *   @access public
    *   @param string $url URL que será enviada a requisição
    *   @param string|array $data Dados que serão enviados
    *   @param array $headers Cabeçalhos adicionais da requisição
    *   @return mixed Reposta da requisição
    */
    public function curlRequest(
        $url,
        array $data,
        array $headers = array(),
        $isGet = true,
        array $additionalOptions = array()
    ) {
        if ($isGet == true) {
            $data = urldecode(http_build_query($data));
            $url = $url . '?' . $data;
        }

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => ($isGet == true)? 'GET' : 'POST',
            CURLOPT_POSTFIELDS => ($isGet == true)? null : json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        );
        
        foreach ($additionalOptions as $key => $value) {
            $options[$key] = $value;
        }
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
    *   IssuuServiceAPI::validFieldJson()
    *
    *   Valida uma variável
    *
    *   @access public
    *   @param object $object
    *   @param string $field Nome da variável a ser validada
    *   @param int $type Corresponde ao tipo que a variável será convertida
    *   @return string Retorna a variável validada ou uma string vazia caso ela não exista
    */
    public function validFieldJson($object, $field, $type = 0)
    {
        if (isset($object->$field))
        {
            if ($type == 0)
            {
                return (string) $object->$field;
            }
            else if ($type == 1)
            {
                return intval($object->$field);
            }
            else if ($type == 2)
            {
                return (is_bool($object->$field))? $object->$field : (($object->$field == 'true')? true : false);
            }
            else if ($type == 3)
            {
                return floatval($object->$field);
            }
            else
            {
                return $object->$field;
            }
        }
        else
        {
            return '';
        }
    }

    /**
    *   IssuuServiceAPI::validFieldXML()
    *
    *   Valida uma variável
    *
    *   @access public
    *   @param array $object
    *   @param string $field Nome da variável a ser validada
    *   @param int $type Corresponde ao tipo que a variável será convertida
    *   @return string Retorna a variável validada ou uma string vazia caso ela não exista
    */
    public function validFieldXML($object, $field, $type = 0)
    {
        if (isset($object[$field]))
        {
            if ($type == 0)
            {
                return (string) $object[$field];
            }
            else if ($type == 1)
            {
                return intval($object[$field]);
            }
            else if ($type == 2)
            {
                return (is_bool($object[$field]))? $object[$field] : (($object[$field] == 'true')? true : false);
            }
            else if ($type == 3)
            {
                return floatval($object[$field]);
            }
            else
            {
                return $object[$field];
            }
        }
        else
        {
            return '';
        }
    }

    /**
    *   IssuuServiceAPI::returnErrorJson()
    *
    *   Lista registros da requisição
    *
    *   @access protected
    *   @param object $response Correspondente ao objeto de resposta da requisição
    *   @return array Array contendo o conteúdo do erro
    */
    protected function returnErrorJson($response)
    {
        return array(
            'stat' => 'fail',
            'code' => (string) $response->_content->error->code,
            'message' => (string) $response->_content->error->message,
            'field' => (string) $response->_content->error->field
        );
    }

    /**
    *   IssuuServiceAPI::returnErrorXML()
    *
    *   Lista registros da requisição
    *
    *   @access protected
    *   @param object $response Correspondente ao objeto de resposta da requisição
    *   @return array Array contendo o conteúdo do erro
    */
    protected function returnErrorXML($response)
    {
        return array(
            'stat' => 'fail',
            'code' => (string) $response->error['code'],
            'message' => (string) $response->error['message'],
            'field' => (string) $response->error['field']
        );
    }

    /**
    *   IssuuServiceAPI::returnSingleResult()
    *
    *   Faz a requisição de um único documento.
    *
    *   @access protected
    *   @param array $params Correspondente aos parâmetros da requisição
    *   @return array Retorna um array com a resposta da requisição
    */
    final protected function returnSingleResult($params)
    {
        $this->setParams($params);
        $response = $this->curlRequest(
            $this->getApiUrl(),
            $this->params,
            $this->headers
        );

        $slug = $this->slug_section;

        if (isset($params['format']) && $params['format'] == 'json')
        {
            $response = json_decode($response);
            $response = $response->rsp;

            if($response->stat == 'ok')
            {
                $result['stat'] = 'ok';
                $result[$slug] = $this->clearObjectJson($response->_content->$slug);

                return $result;
            }
            else
            {
                return $this->returnErrorJson($response);
            }
        }
        else
        {
            $response = new SimpleXMLElement($response);

            if ($response['stat'] == 'ok')
            {
                $result['stat'] = 'ok';
                $result[$slug] = $this->clearObjectXML($response->$slug);

                return $result;
            }
            else
            {
                return $this->returnErrorXML($response);
            }
        }
    }

    /**
    *   IssuuServiceAPI::delete()
    *
    *   Exclui os registros da requisição
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    */
    final public function delete($params = array())
    {
        $params['action'] = $this->delete;
        $this->setParams($params);
        $response = $this->curlRequest(
            $this->getApiUrl(),
            $this->params,
            $this->headers
        );

        if (isset($params['format']) && $params['format'] == 'json')
        {
            $response = json_decode($response);
            $response = $response->rsp;

            if ($response->stat == 'ok')
            {
                return array('stat' => 'ok');
            }
            else
            {
                return $this->returnErrorJson($response);
            }
        }
        else
        {
            $response = new SimpleXMLElement($response);

            if ($response['stat'] == 'ok')
            {
                return array('stat' => 'ok');
            }
            else
            {
                return $this->returnErrorXML($response);
            }
        }
    }

    /**
    *   IssuuServiceAPI::issuuList()
    *
    *   Lista registros da requisição
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    */
    final public function issuuList($params = array())
    {
        $params['page'] = 1;
        $this->setParams($params);

        $response = $this->curlRequest(
            $this->getApiUrl('/publications'),
            $this->params,
            $this->headers,
        );

        $slug = $this->slug_section;

        $response = json_decode($response, true);

        if ($response['results'])
        {
            $result['stat'] = 'ok';
            $result['totalCount'] = (int) $response['count'];
            $result['page'] = (int) $params['page'];
            $result['size'] = (int) $response['pageSize'];
            $result['more'] = !!$response['links']['next'] ? true : false;

            if (!empty($response['results']))
            {
                foreach ($response['results'] as $item) {
                    $result[$slug][] = $this->clearObjectJson($item);
                }
            }

            return $result;
        }
        else
        {
            return $this->returnErrorJson($response);
        }
    }

    /**
    *   IssuuServiceAPI::getApiUrl()
    *
    *   @access public
    *   @param string $endpoint
    *   @return string URL da API de dados do Issuu
    */
    public function getApiUrl($endpoint = '')
    {
        return $this->api_url . $endpoint;
    }


    /**
    *   IssuuServiceAPI::getUploadUrl()
    *
    *   @access public
    *   @param string $slug Slug do documento
    *   @return string URL da API para upload do Issuu
    */
    public function getUploadUrl($slug)
    {
        return $this->buildUrl(false, $slug);
    }

    /**
    *   IssuuServiceAPI::clearObjectXML()
    *
    *   Valida os atributos de um objeto XML
    *
    *   @access protected
    *   @param object $object Correspondente ao objeto XML a ser validado
    */
    abstract protected function clearObjectXML($object);

    /**
    *   IssuuServiceAPI::clearObjectJson()
    *
    *   Valida os atributos de um objeto Json
    *
    *   @access protected
    *   @param object $object Correspondente ao objeto Json a ser validado
    */
    abstract protected function clearObjectJson($object);
}