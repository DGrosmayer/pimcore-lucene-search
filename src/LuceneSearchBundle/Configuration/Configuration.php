<?php

namespace LuceneSearchBundle\Configuration;

use LuceneSearchBundle\Configuration\Categories\CategoriesInterface;
use Pimcore\Extension\Bundle\PimcoreBundleManager;
use Symfony\Component\Filesystem\Filesystem;

class Configuration
{
    const STATE_DEFAULT_VALUES = [
        'forceStart' => false,
        'forceStop'  => false,
        'running'    => false,
        'started'    => null,
        'finished'   => null
    ];

    const SYSTEM_CONFIG_DIR_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle';

    const SYSTEM_CONFIG_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/config.yml';

    const STATE_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/state.cnf';

    const CRAWLER_LOG_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/crawler.log';

    const CRAWLER_PROCESS_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/processing.tmp';

    const CRAWLER_URI_FILTER_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/uri-filter.tmp';

    const CRAWLER_PERSISTENCE_STORE_DIR_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/persistence-store';

    const CRAWLER_TMP_ASSET_DIR_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/tmp-assets';

    const INDEX_DIR_PATH = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/index';

    const INDEX_DIR_PATH_GENESIS = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/index/genesis';

    const INDEX_DIR_PATH_STABLE = PIMCORE_PRIVATE_VAR . '/bundles/LuceneSearchBundle/index/stable';

    /**
     * @var PimcoreBundleManager
     */
    protected $bundleManager;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $systemConfig;

    /**
     * @var CategoriesInterface
     */
    private $categoryService;

    /**
     * Configuration constructor.
     *
     * @param PimcoreBundleManager $bundleManager
     */
    public function __construct(PimcoreBundleManager $bundleManager)
    {
        $this->bundleManager = $bundleManager;
        $this->fileSystem = new FileSystem();
    }

    /**
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->config = $config;
    }

    /**
     * @param $slot
     *
     * @return mixed
     */
    public function getConfig($slot)
    {
        if (is_array($this->config) && array_key_exists($slot, $this->config)) {
            return $this->config[$slot];
        }
    }

    /**
     * @param array $config
     */
    public function setSystemConfig($config = [])
    {
        $this->systemConfig = $config;
    }

    /**
     * @param null $slot
     *
     * @return mixed
     */
    public function getSystemConfig($slot = null)
    {
        if (is_array($this->systemConfig) && array_key_exists($slot, $this->systemConfig)) {
            return $this->systemConfig[$slot];
        }
    }

    /**
     * @param null $slot
     *
     * @return mixed
     */
    public function getStateConfig($slot = null)
    {
        if (!$this->fileSystem->exists(Configuration::STATE_FILE_PATH)) {
            $content = serialize(Configuration::STATE_DEFAULT_VALUES);
            $this->fileSystem->appendToFile(Configuration::STATE_FILE_PATH, $content);
        }

        $data = file_get_contents(self::STATE_FILE_PATH);
        $arrayData = unserialize($data);

        return $slot == null ? $arrayData : $arrayData[$slot];
    }

    /**
     * @param $slot
     * @param $value
     *
     * @throws \Exception
     */
    public function setStateConfig($slot, $value)
    {
        $content = $this->getStateConfig();

        if (!in_array($slot, array_keys($content))) {
            throw new \Exception('invalid state config slot "' . $slot . '"');
        }

        $content[$slot] = $value;

        $this->fileSystem->dumpFile(self::STATE_FILE_PATH, serialize($content));
    }

    /**
     * @param CategoriesInterface $categoryService
     */
    public function setCategoryService(CategoriesInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        if (!$this->categoryService instanceof CategoriesInterface) {
            return [];
        }

        return $this->categoryService->getCategories();
    }
}
