<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 29/10/2016
 * Time: 5:10 PM
 */
namespace App\Http\Controllers;

use App\Libraries\Utils\Err;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Validator;
use DB;

class PostController extends Controller
{
    private $postDao;
    private $topicDao;

    public function __construct(Request $request, Post $post, Topic $topic)
    {
        parent::__construct($request);
        $this->postDao = $post;
        $this->topicDao = $topic;
    }

    public function create($topicId)
    {
        $requestParams = $this->request->all();
        $v = Validator::make($requestParams, [
            'uid' => 'required|integer',
            'content' => 'required|string',
        ]);

        if ($v->fails()) {
            fAbort(403, Err::REQUEST_PARAMS_ERROR);
        }

        $params = [
            'content' => $requestParams['content'],
            'ip' => $this->request->ip(),
        ];

        $uid = $requestParams['uid'];

        $res = [];
        DB::beginTransaction();
        try {
            $res = $this->postDao->createPost($uid, $topicId, $params);
            $this->topicDao->addPost($topicId, $uid);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            app('log')->warning($exception->getMessage());
            fAbort(403, Err::SERVER_INTERNAL_ERROR);
        }

        return [
            'post_id' => $res['post_id'],
        ];
    }

    public function edit($postId)
    {
        $requestParams = $this->request->all();
        $v = Validator::make($requestParams, [
            'uid' => 'required|integer',
            'content' => 'required|string',
        ]);

        if ($v->fails()) {
            fAbort(403, Err::REQUEST_PARAMS_ERROR);
        }

        if (!checkModelResult($this->postDao->getPostInfo($postId))) {
            fAbort(403, Err::POST_NOT_EXIST);
        }

        $params = [
            'content' => $requestParams['content'],
        ];

        $this->postDao->updatePost($postId, $params);

        return [
            'result' => 'ok',
        ];
    }
}