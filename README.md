# Run docker compose up -d
# Run docker exec quasidvach php artisan migrate

GET /topics возвращает все топики

POST /topics создаёт топик, имя берется из параметра name
example: 127.0.0.1:8000/api/topics/?name=b

GET /topics/{id} возвращает топик с данным id
GET /topics/{id}/threads возвращает треды в данном топике
PUT /topics/{id} позволяет изменить название топика через параметр name
DELETE /topics/{id} удаляет топик, все треды в нем и все сообщения в тредах

GET /posts возвращает все посты

POST /topics/{id} создаёт тред в топике, текст берется из параметра body
POST /posts/{id} создаёт сообщение в том же треде, текст берется из параметра body, id отвечаемого сообщения из параметра reply_to

GET /posts/{id} возвращает пост с данным id
GET /posts/{id}/messages возвращает сообщения в данном посте, если пост является тредом
GET /posts/{id}/replies возвращает ответы на пост с данным id
GET /posts/{id}/reply_to возвращает пост, на который отвечает пост с данным id

PUT /posts/{id} позволяет изменить текст поста через параметр body

DELETE /posts/{id} удаляет пост с указанным id, если пост является тредом, то также удаляются все сообщения в нем
При удалении поста у всех ответов на него значение 'reply_to' устанавливается на null
