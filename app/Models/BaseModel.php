<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 28/10/2016
 * Time: 4:03 PM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class BaseModel extends Model
{
    protected $redis = '';

    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->redis = app()->redis;
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new Builder($conn, $grammar, $conn->getPostProcessor());
    }

    public function remember($cacheTTL = 60, $cacheKey = null, $isLocal = false)
    {
        return new BaseCacheModel($this, $cacheTTL, $cacheKey, BaseCacheModel::REMEMBER, $isLocal);
    }

    public function reload($cacheTTL = 60, $cacheKey = null, $isLocal = false)
    {
        return new BaseCacheModel($this, $cacheTTL, $cacheKey, BaseCacheModel::RELOAD, $isLocal);
    }

    public function forget($cacheKey = null, $isLocal = false)
    {
        return new BaseCacheModel($this, 0, $cacheKey, BaseCacheModel::FORGET, $isLocal);
    }

    public function ignore($cacheKey = null, $isLocal = false)
    {
        return new BaseCacheModel($this, 0, $cacheKey, BaseCacheModel::IGNORE, $isLocal);
    }
}