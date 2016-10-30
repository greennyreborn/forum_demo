# -*- coding: utf-8 -*-

import time
import md5


def generate_password(raw):
    m = md5.new()
    m.update(raw)
    return m.hexdigest()


def generate_users():
    users = []
    for i in range(10):
        users.append({
            'uid': 1000000000 + i,
            'username': 'test' + str(i),
            'password': generate_password('12345678'),
            'avatar': '',
            'created_at': int(time.time()),
            'updated_at': int(time.time())
        })
    return users
