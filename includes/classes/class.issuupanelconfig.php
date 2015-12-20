<?php

class IssuuPanelConfig
{

	private $issuuPanelDebug;

    private $mobileDetect;

	private $issuuPanelCatcher;

    private $issuuPanelSimpleReader;

    private $issuuPanelCron;

    private $issuuPanelOptionEntity;

    private $issuuPanelHookManager;

    private $issuuPanelOptionEntityManager;

    private $issuuPanelCacheManager;

    private $issuuServiceApi = array(
        'IssuuDocument' => null,
        'IssuuFolder' => null,
        'IssuuBookmark' => null,
        'IssuuDocumentEmbed' => null,
    );

    /*
    |----------------------------------------
    |  VARIABLES
    |----------------------------------------
    */
    private $issuu_shortcode_index = 0;
    private $iterator_per_template = array(
        '404' => 0,
        'page' => 0,
        'single' => 0,
        'tag' => 0,
        'author' => 0,
        'archive' => 0,
        'attachment' => 0,
        'category' => 0,
        'date' => 0,
        'day' => 0,
        'feed' => 0,
        'front_page' => 0,
        'home' => 0,
        'month' => 0,
        'search' => 0,
        'tax' => 0,
        'taxonomy_hierarchical' => 0,
        'time' => 0,
        'year' => 0,
    );

    /*
    |----------------------------------------
    |  CONSTANTS
    |----------------------------------------
    */
    private $ISSUU_PANEL_CAPABILITIES = array(
        'Administrator' => 'manage_options',
        'Editor' => 'edit_private_pages',
        'Author' => 'upload_files'
    );

    public function __construct($issuuPanelOptionEntity, $issuuPanelOptionEntityManager)
    {
        $this->setOptionEntity($issuuPanelOptionEntity);
        $this->setOptionEntityManager($issuuPanelOptionEntityManager);

        // IssuuPanelDebug
        $this->issuuPanelDebug = new IssuuPanelDebug($this->getOptionEntity()->getDebug());
        $this->issuuPanelDebug->appendMessage("-----------------------", false);
        $this->issuuPanelDebug->appendMessage("Browser: " . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'));

        // Mobile_Detect
        $this->mobileDetect = new Mobile_Detect();

        // IssuuPanelCatcher
        $this->issuuPanelCatcher = new IssuuPanelCatcher();

        // IssuuPanelSimpleReader
        $this->issuuPanelSimpleReader = new IssuuPanelSimpleReader();

        // IssuuPanelCron
        $this->issuuPanelCron = new IssuuPanelCron();

        // IssuuPanelHookManager
        $this->issuuPanelHookManager = new IssuuPanelHookManager();

        // IssuuPanelCacheManager
        $this->issuuPanelCacheManager = new IssuuPanelCacheManager($this->getOptionEntity());

        // IssuuServiceApi
        $this->issuuServiceApi = array(
            'IssuuDocument' => new IssuuDocument(
                $this->getOptionEntity()->getApiKey(),
                $this->getOptionEntity()->getApiSecret()
            ),
            'IssuuFolder' => new IssuuFolder(
                $this->getOptionEntity()->getApiKey(),
                $this->getOptionEntity()->getApiSecret()
            ),
            'IssuuBookmark' => new IssuuBookmark(
                $this->getOptionEntity()->getApiKey(),
                $this->getOptionEntity()->getApiSecret()
            ),
            'IssuuDocumentEmbed' => new IssuuDocumentEmbed(
                $this->getOptionEntity()->getApiKey(),
                $this->getOptionEntity()->getApiSecret()
            ),
        );
    }

    public function getNextIterator()
    {
        $this->issuu_shortcode_index++;
        return $this->issuu_shortcode_index;
    }

    public function getNextIteratorByTemplate()
    {
        $key = $this->getIssuuPanelCatcher()->getTemplate();
        $this->iterator_per_template[$key]++;
        return $this->iterator_per_template[$key];
    }

    public function getCapability()
    {
        $name = $this->getOptionEntity()->getEnabledUser();
        if (is_null($name) || !isset($this->ISSUU_PANEL_CAPABILITIES[$name]))
            return $this->ISSUU_PANEL_CAPABILITIES;
        return $this->ISSUU_PANEL_CAPABILITIES[$name];
    }

    public function isBot()
    {
        $utilities = $this->mobileDetect->getUtilities();
        $bots = spliti("\|", $utilities['Bot']);
        $userAgent = $this->mobileDetect->getHttpHeader('USER_AGENT');
        foreach ($bots as $bot) {
            if (strpos($userAgent, $bot) !== false)
            {
                return true;
            }
        }
        $mobileBots = spliti("\|", $utilities['MobileBot']);
        foreach ($mobileBots as $bot) {
            if (strpos($userAgent, $bot) !== false)
            {
                return true;
            }
        }

        return false;
    }

    public function getIssuuServiceApi($name)
    {
        $valids = array(
            'IssuuDocument',
            'IssuuFolder',
            'IssuuBookmark',
            'IssuuDocumentEmbed'
        );
        if (!in_array($name, $valids))
            return null;

        return $this->issuuServiceApi[$name];
    }

    public function getHookManager()
    {
        return $this->issuuPanelHookManager;
    }

    public function getCacheManager()
    {
        return $this->issuuPanelCacheManager;
    }

    public function getIssuuPanelCatcher()
    {
        return $this->issuuPanelCatcher;
    }

    private function setOptionEntity($issuuPanelOptionEntity)
    {
        $this->issuuPanelOptionEntity = $issuuPanelOptionEntity;
    }

    public function getOptionEntity()
    {
        return $this->issuuPanelOptionEntity;
    }

    private function setOptionEntityManager($issuuPanelOptionEntityManager)
    {
        $this->issuuPanelOptionEntityManager = $issuuPanelOptionEntityManager;
    }

    public function getOptionEntityManager()
    {
        return $this->issuuPanelOptionEntity;
    }

    /**
     * Gets the value of issuuPanelDebug.
     *
     * @return mixed
     */
    public function getIssuuPanelDebug()
    {
        return $this->issuuPanelDebug;
    }

    /**
     * Gets the value of mobileDetect.
     *
     * @return mixed
     */
    public function getMobileDetect()
    {
        return $this->mobileDetect;
    }

    /**
     * Gets the value of issuuPanelSimpleReader.
     *
     * @return mixed
     */
    public function getIssuuPanelSimpleReader()
    {
        return $this->issuuPanelSimpleReader;
    }

    /**
     * Gets the value of issuuPanelCron.
     *
     * @return mixed
     */
    public function getIssuuPanelCron()
    {
        return $this->issuuPanelCron;
    }
}

// IssuuPanelConfig::init();

// function issuu_panel_debug($message)
// {
// 	IssuuPanelConfig::getIssuuPanelDebug()->appendMessage($message);
// }