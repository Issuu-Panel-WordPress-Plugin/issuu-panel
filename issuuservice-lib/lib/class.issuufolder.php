<?php

if (!class_exists('IssuuServiceAPI'))
{
    require(dirname(__FILE__) . '/class.issuuserviceapi.php');
}

/**
*   Classe IssuuFolder
*
*   @author Pedro Marcelo de Sá Alves
*   @link https://github.com/pedromarcelojava/
*   @version 1.2
*/
class IssuuFolder extends IssuuServiceAPI
{

    /**
    *   Método de listagem da seção Folder
    *
    *   @access protected
    *   @var string
    */
    protected $list = 'issuu.folders.list';

    /**
    *   Método de exclusão da seção Folder
    *
    *   @access protected
    *   @var string
    */
    protected $delete = 'issuu.folder.delete';

    /**
    *   Slug da seção
    *
    *   @access protected
    *   @var string
    */
    protected $slug_section = 'folder';

    /**
    *   IssuuFolder::add()
    *
    *   Relacionado ao método issuu.folder.add da API.
    *   Cria uma pasta vazia na conta. Documentos e marcadores podem
    *   ser adicionados as pastas.
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    *   @return array Retorna um array com a resposta da requisição
    */
    public function add($params)
    {
        $params['action'] = 'issuu.folder.add';
        
        return $this->returnSingleResult($params);
    }

    /**
    *   IssuuBookmark::stackList()
    *
    *   Lista stacks
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    */
    public function stackList($params = array())
    {
        $this->setParams($params);

        $response = $this->curlRequest(
            $this->getApiUrl('/stacks'),
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
     *  IssuuFolder::delete()
     * 
     * Deleta uma ou mais stacks.
     */
    public function delete($params = array())
    {
        $this->setParams($params);
        foreach ($params['stackIds'] as $slug) {
            $response = $this->curlRequest(
                $this->getApiUrl('/stacks/'.$slug),
                array(),
                $this->headers,
                'DELETE'
            );
        }

        return array('stat' => 'ok');
    }

    /**
    *   IssuuFolder::update()
    *
    *   Relacionado ao método issuu.folder.update da API.
    *   Atualiza os dados de uma determinada pasta.
    *
    *   @access public
    *   @param array $params Correspondente aos parâmetros da requisição
    *   @return array Retorna um array com a resposta da requisição
    */
    public function update($params = array())
    {
        $params['action'] = 'issuu.folder.update';

        return $this->returnSingleResult($params);
    }

    /**
    *   IssuuFolder::clearObjectJson()
    *
    *   Valida e formata os atributos do objeto da pasta.
    *
    *   @access protected
    *   @param object $folder Correspondente ao objeto da pasta
    *   @return object Retorna um novo objeto da pasta devidamente validado
    */
    protected function clearObjectJson($folder)
    {
        $fold = (object) $folder;

        return $fold;
    }

    /**
    *   IssuuFolder::clearObjectXML()
    *
    *   Valida e formata os atributos do objeto da pasta.
    *
    *   @access protected
    *   @param object $folder Correspondente ao objeto da pasta
    *   @return object Retorna um novo objeto da pasta devidamente validado
    */
    protected function clearObjectXML($folder)
    {
        $fold = new stdClass();

        $fold->folderId = $this->validFieldXML($folder, 'folderId');
        $fold->username = $this->validFieldXML($folder, 'username');
        $fold->name = $this->validFieldXML($folder, 'name');
        $fold->description = $this->validFieldXML($folder, 'description');
        $fold->items = $this->validFieldXML($folder, 'items', 1);
        $fold->itemCount = $this->validFieldXML($folder, 'itemCount', 1);
        $fold->ep = $this->validFieldXML($folder, 'ep', 1);
        $fold->created = $this->validFieldXML($folder, 'created');

        return $fold;
    }
}