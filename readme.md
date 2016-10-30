## Intro
代码用PHP编写，采用Laravel框架

## Install


```bash
git clone https://github.com/greennyreborn/forum_demo.git

cd forum_demo

composer install

```

## Deploy

采用 nginx + php-fpm 部署

## API list

* Create user

```js
{
	method: 'POST',
	url: '/user',
	params: {
		"username": "test",
		"avatar": "",
		"password": "12345678"
	}
}
```
* Create Topic

```js
{
	method: 'POST',
	url: '/topic/user/{uid}',
	params: {
		"title": "hello",
		"type": "1",
		"content": "test"
	}
}
```
* Edit Topic

```js
{
	method: 'PUT',
	url: '/topic/{topic_id}',
	params: {
		"title": "hello",
		"type": "1",
		"content": "test"
	}
}
```
* Create Post

```js
{
	method: 'POST',
	url: '/post/topic/{topic_id}',
	params: {
		"uid": "4164485977",
		"content": "啦啦啦"
	}
}
```
* Edit Post

```js
{
	method: 'PUT',
	url: '/post/{post_id}',
	params: {
		"uid": "4164485977",
		"content": "啦啦啦"
	}
}
```
* Show topic list

```js
{
	method: 'GET',
	url: '/topic/list?offset=0&size=20',
}
```
* Show topic detail

```js
{
	method: 'GET',
	url: '/topic/{topic_id}?offset=0&size=20',
}
```

## Scripts

脚本用python编写

需要 mysql-connector 和 requests 模块

* 创建用户

```bash
python scripts/create_user.py 
```

* 创建 Topic & Post

```bash
python scripts/api_test.py
```




 

