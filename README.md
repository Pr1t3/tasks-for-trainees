# Задание на 9 неделю
Сделать CRUD приложение для работы с яндекс диском по API, используя библиотеку https://github.com/jack-theripper/yandex  
1. Установить библиотеку через composer  
2. Получить API токен https://yandex.ru/dev/oauth/doc/dg/tasks/get-oauth-token-docpage/  
3. Изучить документацию https://yandex.ru/dev/disk/api/concepts/about-docpage/  
4. Реализовалть добавление, просмотр и удаление файлов на любой странице  
5. (Дополнительно) Реализовать изменение файлов 

# Запуск
1. Задать в .env файле переменную `YANDEX_TOKEN`
1. Для выбора версии необходимо указать --type(-t) cli/web
## CLI версия
### Команды(--command/-c)
#### upload
1. Название файла на Яндекс Диске: --name(-n)
2. Путь до файла на вашем устройстве или содержимое файла: --file(-f) или --content(-C)
#### view
1. Название файла на Яндекс Диске: --name(-n)
#### delete
1. Название файла на Яндекс Диске: --name(-n)
#### list
#### edit
1. Название файла на Яндекс Диске: --name(-n)
2. Путь до файла на вашем устройстве или содержимое файла: --file(-f) или --content(-C)

Пример запуска: `php provider.php --type cli --command upload --name example.txt --content "Hello, World!"`
## Web версия
### Endpoints
#### /
Список всех файлов
#### /files?path=...
Показывает содержимое файла, путь до которого передан в query string с названием path

Пример: `/files?path=disk:/example.txt`
#### /upload
Показывает форму для загрузки файла
#### /edit
Показывает форму для изменения содержимого файла
#### /delete
Показывает форму для удаления файла
#### /api/files
Возвращает список файлов в json формате
#### /api/files?path=...
Возвращает содеримое файла в json формате

Пример запуска: `php provider.php --type web` 