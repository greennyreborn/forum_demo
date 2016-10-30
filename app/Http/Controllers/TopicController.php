<?php
/**
 * Created by IntelliJ IDEA.
 * User: michael
 * Date: 29/10/2016
 * Time: 4:47 PM
 */
namespace App\Http\Controllers;

use App\Libraries\Utils\Err;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class TopicController extends Controller
{
    private $topicDao;

    private $userDao;

    public function __construct(Request $request, Topic $topic, User $user)
    {
        parent::__construct($request);
        $this->topicDao = $topic;
        $this->userDao = $user;
    }

    public function getList()
    {
        $params = [
            'offset' => $this->request->query('offset', ''),
            'size' => $this->request->query('size', ''),
        ];
        $params = array_filter($params);
        $topics = $this->topicDao->getTopicList($params);

        return [
            'topic_list' => $topics,
        ];
    }

    public function detail($topicId)
    {
        if (!checkModelResult($this->topicDao->getTopicInfo($topicId))) {
            fAbort(403, Err::TOPIC_NOT_EXIST);
        }

        $params = [
            'offset' => $this->request->query('offset', ''),
            'size' => $this->request->query('size', ''),
        ];
        $params = array_filter($params);

        $topic = $this->topicDao->getTopicDetail($topicId, $params);

        return $topic;
    }

    /**
     * create a new topic
     *
     * @param $uid
     * @return array
     */
    public function create($uid)
    {
        $requestParams = $this->request->all();
        $v = Validator::make($requestParams, [
            'title' => 'required|string',
            'type' => 'required|integer',
            'content' => 'required|string',
        ]);

        if ($v->fails()) {
            fAbort(403, Err::REQUEST_PARAMS_ERROR);
        }

        if (!checkModelResult($this->userDao->getUserByUid($uid))) {
            fAbort(403, Err::USER_NOT_EXIST);
        }

        $params = [
            'title' => $requestParams['title'],
            'type' => $requestParams['type'],
            'content' => $requestParams['content'],
            'ip' => $this->request->ip(),
        ];

        $res = $this->topicDao->createTopic($uid, $params);

        return [
            'topic_id' => $res['topic_id'],
        ];
    }

    public function edit($topicId)
    {
        $params = [
            'title' => $this->request->input('title', ''),
            'type' => $this->request->input('type', ''),
            'content' => $this->request->input('content', ''),
        ];

        $params = array_filter($params); // remove empty value

        if (empty($params)) {
            fAbort(403, Err::REQUEST_PARAMS_ERROR);
        }

        if (!checkModelResult($this->topicDao->getTopicInfo($topicId))) {
            fAbort(403, Err::TOPIC_NOT_EXIST);
        }

        $this->topicDao->updateTopic($topicId, $params);

        return [
            'result' => 'ok',
        ];
    }
}