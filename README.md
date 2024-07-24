# After cloning the repo run the following:
# docker compose up -d
# docker exec quasidvach php artisan migrate

GET /api/topics возвращает все топики

POST /api/topics создаёт топик, имя берется из параметра name  
example: 127.0.0.1:8000/api/topics/?name=b

GET /api/topics/{id} возвращает топик с данным id  
GET /api/topics/{id}/threads возвращает треды в данном топике  
PUT /api/topics/{id} позволяет изменить название топика через параметр name  
DELETE /api/topics/{id} удаляет топик, все треды в нем и все сообщения в тредах  

GET /api/posts возвращает все посты

POST /api/topics/{id} создаёт тред в топике, текст берется из параметра body  
POST /api/posts/{id} создаёт сообщение в том же треде, текст берется из параметра body, id отвечаемого сообщения из параметра reply_to

GET /api/posts/{id} возвращает пост с данным id  
GET /api/posts/{id}/messages возвращает сообщения в данном посте, если пост является тредом  
GET /api/posts/{id}/replies возвращает ответы на пост с данным id  
GET /api/posts/{id}/reply_to возвращает пост, на который отвечает пост с данным id  

PUT /api/posts/{id} позволяет изменить текст поста через параметр body

DELETE /api/posts/{id} удаляет пост с указанным id, если пост является тредом, то также удаляются все сообщения в нем  
При удалении поста у всех ответов на него значение 'reply_to' устанавливается на null
