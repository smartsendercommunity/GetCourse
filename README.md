# GetCourse

Передача данных из GetCourse в Smart Sender через функцию "Вызвать url" в процесах GetCourse

Требуется наличие идентификатора userId в карточке пользователя GetCourse (ssId={object.user._ })

Функции:
1. Добавить тег пользователю (addTags[]=название_тега)
2. Удалить тег у пользователя (delTags[]=название_тега)
3. Подписать пользователя на воронку (addFunnels[]=название_воронки)
4. Отписать пользователя от воронки (delFunnels[]=название_воронки)
5. Запустить пользователю событие (triggers[]=название_события)
6. Обновить пользователю переменные (variables[название_переменной]=значение_переменной)

Тип используемого запроса - POST
