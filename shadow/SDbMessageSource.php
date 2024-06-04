<?php
/**
 * Created by PhpStorm.
 * Project: morkovka
 * User: lxShaDoWxl
 * Date: 19.08.15
 * Time: 13:45
 */
namespace shadow;

use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\caching\Cache;
use yii\db\Connection;
use yii\db\Query;
use yii\i18n\MessageSource;

class SDbMessageSource extends MessageSource
{
    /**
     * Prefix which would be used when generating cache key.
     */
    const CACHE_KEY_PREFIX = 'SDbMessageSource';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     * After the DbMessageSource object is created, if you want to change this property, you should only assign
     * it with a DB connection object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $db = 'db';
    /**
     * @var Cache|array|string the cache object or the application component ID of the cache object.
     * The messages data will be cached using this cache object. Note, this property has meaning only
     * in case [[cachingDuration]] set to non-zero value.
     * After the DbMessageSource object is created, if you want to change this property, you should only assign
     * it with a cache object.
     * Starting from version 2.0.2, this can also be a configuration array for creating the object.
     */
    public $cache = 'cache';
    /**
     * @var string the name of the source message table.
     */
    public $sourceMessageTable = '{{%l_source_message}}';
    /**
     * @var string the name of the translated message table.
     */
    public $messageTable = '{{%l_message}}';
    /**
     * @var integer the time in seconds that the messages can remain valid in cache.
     * Use 0 to indicate that the cached data will never expire.
     * @see enableCaching
     */
    public $cachingDuration = 0;
    /**
     * @var boolean whether to enable caching translated messages
     */
    public $enableCaching = false;

    /**
     * Initializes the DbMessageSource component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * Configured [[cache]] component would also be initialized.
     * @throws InvalidConfigException if [[db]] is invalid or [[cache]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        if ($this->enableCaching) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
    }

    /**
     * Loads the message translation for the specified language and category.
     * If translation for specific locale code such as `en-US` isn't found it
     * tries more generic `en`.
     *
     * @param string $category the message category
     * @param string $language the target language
     * @return array the loaded messages. The keys are original messages, and the values
     * are translated messages.
     */
    protected function loadMessages($category, $language)
    {
        if ($this->enableCaching) {
            $key = [
                __CLASS__,
                $category,
                $language,
            ];
            $key_default = [
                __CLASS__,
                $category,
                'defaults_message',
            ];
            $messages = $this->cache->get($key);
            $defaults = $this->cache->get($key_default);
            if ($messages === false) {
                $messages = $this->loadMessagesFromDb($category, $language);
                $this->cache->set($key, $messages, $this->cachingDuration);
                $defaults = $this->loadDefaultsMessages($category);
                $this->cache->set($key_default, $defaults, $this->cachingDuration);
            }
        } else {
            $messages = $this->loadMessagesFromDb($category, $language);
            $defaults = $this->loadDefaultsMessages($category);
        }
        $this->on(self::EVENT_MISSING_TRANSLATION, function ($event) use ($defaults) {
            if (isset($defaults[$event->message])) {
                $event->translatedMessage = $defaults[$event->message];
            } else {
                $event->translatedMessage = $event->message;
            }
        });
        return $messages;
    }
    /**
     * Loads the defaults messages from database.
     * You may override this method to customize the message storage in the database.
     * @param string $category the message category.
     * @return array the messages loaded from database.
     */
    protected function loadDefaultsMessages($category)
    {
        $mainQuery = new Query();
        $mainQuery->select(['t1.message message', 't1.default default'])
            ->from(["$this->sourceMessageTable t1"])
            ->where('t1.category = :category')
            ->params([':category' => $category]);
        $defaults = $mainQuery->createCommand($this->db)->queryAll();
        return ArrayHelper::map($defaults, 'message', 'default');
    }
    /**
     * Loads the messages from database.
     * You may override this method to customize the message storage in the database.
     * @param string $category the message category.
     * @param string $language the target language.
     * @return array the messages loaded from database.
     */
    protected function loadMessagesFromDb($category, $language)
    {
        $mainQuery = new Query();
        $mainQuery->select(['t1.message message', 't2.translation translation'])
            ->from(["$this->sourceMessageTable t1", "$this->messageTable t2"])
            ->where('t1.id = t2.id AND t1.category = :category AND t2.language = :language')
            ->params([':category' => $category, ':language' => $language]);
        $fallbackLanguage = substr($language, 0, 2);
        if ($fallbackLanguage != $language) {
            $fallbackQuery = new Query();
            $fallbackQuery->select(['t1.message message', 't2.translation translation'])
                ->from(["$this->sourceMessageTable t1", "$this->messageTable t2"])
                ->where('t1.id = t2.id AND t1.category = :category AND t2.language = :fallbackLanguage')
                ->andWhere("t2.id NOT IN (SELECT id FROM $this->messageTable WHERE language = :language)")
                ->params([':category' => $category, ':language' => $language, ':fallbackLanguage' => $fallbackLanguage]);
            $mainQuery->union($fallbackQuery, true);
        }
        $messages = $mainQuery->createCommand($this->db)->queryAll();
        return ArrayHelper::map($messages, 'message', 'translation');
    }
}