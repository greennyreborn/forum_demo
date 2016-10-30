<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 4:06 PM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

class BaseCacheModel
{
    const REMEMBER = -1;
    const IGNORE = -2;
    const FORGET = -3;
    const RELOAD = -4;

    private $model;
    private $cacheTTL;
    private $cacheKey;
    private $operation;

    protected $localRedis = null;

    public function __construct(BaseModel $model, $cacheTTL = 60, $cacheKey = null, $operation = self::REMEMBER)
    {
        $this->model = $model;
        $this->cacheTTL = $cacheTTL;
        $this->cacheKey = $cacheKey;
        $this->operation = $operation;
        $this->_initWork();
    }

    public function __call($method, $arguments)
    {
        if ($this->cacheTTL > 0 && $this->operation == self::REMEMBER) {
            $this->cacheKey = $this->generateCacheKey($method, $arguments);
            $cached = $this->getCacheContent();
            if ($cached) {
                return $cached;
            } else {
                $result = call_user_func_array([$this->model, $method], $arguments);
                $this->cache($result);
                return $result;
            }
        } elseif ($this->operation == self::IGNORE) {
            $result = call_user_func_array([$this->model, $method], $arguments);
            return $result;
        } elseif ($this->operation == self::FORGET) {
            $this->cacheKey = $this->generateCacheKey($method, $arguments);
            $this->clearCache();
            $result = call_user_func_array([$this->model, $method], $arguments);
            return $result;
        } elseif ($this->operation == self::RELOAD) {
            $result = call_user_func_array([$this->model, $method], $arguments);
            $this->cacheKey = $this->generateCacheKey($method, $arguments);
            $this->cache($result);
            return $result;
        }

        return null;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getCacheContent()
    {
        $res = null;

        $redis = app()->redis;
        $res = $redis->get($this->cacheKey);

        $result = null;
        if (is_null($res) == false) {
            $result = json_decode($res, true);
        }

        return collect($result);
    }

    protected function cache($items)
    {
        if ($items && $items instanceof Collection) {
            $items = $items->toArray();
        }
        if ($items) {
            $value_str = json_encode($items);
            app()->redis->set($this->cacheKey, $value_str, 'EX', $this->cacheTTL);
        }
    }

    protected function clearCache()
    {
        if ($this->cacheKey) {
            app()->redis->del($this->cacheKey);
        }
    }

    public function generateCacheKey($method, $arguments)
    {
        if (isset($this->cacheKey)) {
            return $this->cacheKey;
        }
        $argumentStr = '';
        foreach ($arguments as $argument) {
            $argumentStr .= ".". json_encode($argument);
        }
        return "model:".md5(get_class($this->model)."#".$method.'#'. $argumentStr);
    }

    private function _initWork()
    {
        if ($this->operation == self::FORGET && isset($this->cacheKey)) {
            $this->clearCache();
        }
    }
}