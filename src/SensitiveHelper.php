<?php

/**
 * (c) linshaowl <linshaowl@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lswl\Sensitive;

use DfaFilter\HashMap;

/**
 * 敏感词辅助
 */
class SensitiveHelper extends \DfaFilter\SensitiveHelper
{
    protected static $instance;

    /**
     * 排除字符串
     * @var array
     */
    protected $exceptWords = [];

    public function __construct()
    {
        // 初始化敏感词树
        $this->wordTree = new HashMap();
    }

    /**
     * {@inheritdoc}
     */
    public static function init()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * 设置排除字符串
     * @param array $words
     * @return $this
     */
    public function setExcept(array $words)
    {
        if (!empty($words)) {
            $this->exceptWords = array_merge($this->exceptWords, $words);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTree($sensitiveWords = null, bool $isReset = false)
    {
        if (empty($sensitiveWords)) {
            return $this;
        }

        $this->wordTree = $isReset ? new HashMap() : ($this->wordTree ?: new HashMap());

        foreach ($sensitiveWords as $word) {
            $this->buildWordToTree($word);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function yieldToReadFile($filepath)
    {
        return parent::yieldToReadFile($filepath);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildWordToTree($word = '')
    {
        if (in_array($word, $this->exceptWords)) {
            return;
        }

        parent::buildWordToTree($word);
    }
}
