<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 29/10/2016
 * Time: 4:25 PM
 */
namespace App\Models;

use App\Libraries\Utils\Err;
use Illuminate\Database\Eloquent\Collection;

class Topic extends BaseModel
{
    const TYPE_DEFAULT = 1; // 默认主题

    const SIZE = 50;

    protected $table = 'topic';

    protected $hidden = ['id', 'updated_at'];

    protected $dateFormat = 'U';

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function lastReplyUser()
    {
        return $this->belongsTo(User::class, 'last_reply_uid', 'uid');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'topic_id', 'topic_id');
    }

    public function createTopic($authorId, $params)
    {
        $now = time();
        $this->topic_id = $this->generateTopicId($params['ip']);
        $this->title = $params['title'];
        $this->type = isset($params['type']) ? $params['type'] : self::TYPE_DEFAULT;
        $this->content = $params['content'];
        $this->uid = $authorId;
        $this->title = $params['title'];
        $this->last_edit_time = $now;
        $this->last_reply_time = $now;

        $id = 0;
        try {
            if ($this->save()) {
                $id = $this->id;
            }
        } catch (\Exception $exception) {
            app('log')->warning($exception->getMessage());
            fAbort(403, Err::TOPIC_CREATE_ERROR);
        }

        $result = [
            'id' => $id,
            'topic_id' => $this->topic_id,
        ];

        return $result;
    }

    public function updateTopic($topicId, $params)
    {
        $params['last_edit_time'] = time();
        $this->where('topic_id', $topicId)->update($params);
    }

    public function addPost($topicId, $uid)
    {
        $topic = $this->where('topic_id', $topicId);
        $topic->update([
            'last_reply_time' => time(),
            'last_reply_uid' => $uid,
        ]);
        $topic->increment('reply_num');
    }

    /**
     * @param $topicId
     * @return Collection
     */
    public function getTopicInfo($topicId)
    {
        return $this->where('topic_id', $topicId)->first();
    }

    /**
     * @param $params
     * @return Collection
     */
    public function getTopicList($params)
    {
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        $size = isset($params['size']) ? $params['size'] : self::SIZE;
        return $this->getTopicListFromDB($offset, $size);
    }

    /**
     * @param int $offset
     * @param int $size
     * @return Collection
     */
    public function getTopicListFromDB($offset = 0, $size = self::SIZE)
    {
        return $this->with([
            'user' => function ($query) {
                $query->select('uid', 'username');
            },
            'lastReplyUser' => function ($query) {
                $query->select('uid', 'username');
            },
        ])
            ->skip($offset)
            ->take($size)
            ->get();
    }

    /**
     * @param $topicId
     * @param int $offset
     * @param int $size
     * @return Collection
     */
    public function getTopicDetail($topicId, $offset = 0, $size = self::SIZE)
    {
        return $this->getTopicDetailFromBD($topicId, $offset, $size);
    }

    /**
     * @param $topicId
     * @param int $offset
     * @param int $size
     * @return Collection
     */
    public function getTopicDetailFromBD($topicId, $offset = 0, $size = self::SIZE)
    {
        return $this->where('topic_id', $topicId)
            ->with([
                'user' => function ($query) {
                    $query->select('uid', 'username');
                },
                'posts' => function ($query) use ($offset, $size) {
                    $query->skip($offset)
                        ->take($size)
                        ->orderBy('id', 'ASC');
                },
                'posts.user' => function ($query) {
                    $query->select('uid', 'username');
                },
            ])
            ->first();
    }

    protected function generateTopicId($ip)
    {
        $long = ip2long($ip);
        $now_time = time();
        $id = $long + $now_time;
        $sec = explode(" ", microtime());
        $id = $id + intval($sec[0] * 1000000);
        $id = $id + mt_rand(1000, 999999999);

        return $id;
    }

}
