# -*- coding: utf-8 -*-

import random
import string
import json
import requests

import config
import user

user_data = random.sample(user.generate_users(), 5)  # 从10个用户数据中随机取5个
api_urls = config.api_urls

random_str_list = ['速', '度', '快', '坚', '实', '的', '放', '假', '阿', '斯', '蒂', '芬', '家', '电', '及', '覅', '司', '法', '局', '阿', '斯', '顿', '发', '送', '到', '福', '建', '省', '的', '看', '法']

se = requests.session()

def generate_topic():
    topic = {
        'title': string.join(random.sample(random_str_list, 5), ''),
        'type': '1',
        'content': string.join(random.sample(random_str_list, 20), '')
    }
    return topic


def create_topic():
    topic_ids = []
    for index, data in enumerate(user_data):
        url = api_urls['create_topic'] % data['uid']
        for i in range(4):  # 每个人创建4条评论
            topic_data = generate_topic()
            re = se.post(url, data=topic_data)
            try:
                result = json.loads(re.content)
            except:
                print re.content
            else:
                pass
            if 'topic_id' in result:
                topic_ids.append(result['topic_id'])
                print 'create topic success! topic_id: %s' % result['topic_id']
            else:
                print re.content

    return topic_ids


def create_post(topics):
    all_users = user.generate_users()
    for topic_id in topics:
        post_num = random.randint(5, 10)  # 随机回复5~10条
        url = api_urls['create_post'] % str(topic_id)
        for i in range(post_num):
            post_data = {
                'uid': random.choice(all_users)['uid'],
                'content': string.join(random.sample(random_str_list, 25), '')
            }
            re = se.post(url, data=post_data)
            try:
                result = json.loads(re.content)
            except:
                print re.content
            else:
                if 'post_id' in result:
                    print 'create post success! post_id: %s' % result['post_id']
                else:
                    print re.content




topics = create_topic()
create_post(topics)
