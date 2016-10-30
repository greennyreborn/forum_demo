<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 29/10/2016
 * Time: 5:11 PM
 */
namespace App\Models;

use App\Libraries\Utils\Err;
use Illuminate\Support\Collection;

class Post extends BaseModel
{
    protected $table = 'post';

    protected $dateFormat = 'U';

    protected $hidden = ['id', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topic_id');
    }

    public function createPost($authorId, $topicId, $params)
    {
        $now = time();
        $this->post_id = $this->generatePostId($params['ip']);
        $this->content = $params['content'];
        $this->topic_id = $topicId;
        $this->uid = $authorId;
        $this->last_edit_time = $now;

        $id = 0;
        try {
            if ($this->save()) {
                $id = $this->id;
            }
        } catch (\Exception $exception) {
            app('log')->warning($exception->getMessage());
            fAbort(403, Err::POST_CREATE_ERROR);
        }

        $result = [
            'id' => $id,
            'post_id' => $this->post_id,
        ];

        return $result;
    }

    public function updatePost($postId, $params)
    {
        $params['last_edit_time'] = time();
        $this->where('post_id', $postId)->update($params);
    }

    /**
     * @param $postId
     * @return Collection
     */
    public function getPostInfo($postId)
    {
        return $this->where('post_id', $postId)->first();
    }

    protected function generatePostId($ip)
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
