# Cloud&#64;Mail.Ru Downloader

Многопоточное скачивание из облака [Mail.Ru](http://cloud.mail.ru/) по публичной ссылки. Авторизация в Mail.Ru не требуется.

- Скрипт консольный, написан на PHP.
- Для скачивания используется консольный загрузчик [Aria2c](https://aria2.github.io/).
- Скрипт умеет корректно обрабатывать папки в облаке любой вложенности.
- Поддерживается докачка файлов.
- Для работы скрипта нужно установить php на компьютер, например отсюда http://windows.php.net/download/ (если уже установлен какой-нибудь Веб-сервер, например, [Denwer](http://www.denwer.ru/) или [OpenServer](http://open-server.ru/), то php от него тоже подойдет).
- Скрипт работает в PHP версий *5.x.x-7.2.x*.

## Настройка PHP
В `php.ini` должно быть активировано openssl-расширение:
>extension_dir = "ext"\
>extension=php_openssl.dll

### Видео-пример:
[![Cloud.MailRu.Downloader Video example](https://img.youtube.com/vi/WnJyXEdEqfI/0.jpg)](https://www.youtube.com/watch?v=WnJyXEdEqfI)

## Порядок работы

- В файл `links.txt` записать публичные ссылки на скачивание с облака вида https://cloud.mail.ru/public/4A7D/qjuSenWvG по одной на строку.
- В самом скрипте можно указать папку, куда будут скачиваться файлы, по-умолчанию это папка `downloads` рядом со скриптом.
- Запустить скрипт: `php cloud@mail.ru_downloader.php`
- Скрипт сформирует файл с прямыми ссылками на скачивание `input.txt`.
- После чего запустится Aria2c Downloader, который скачает файлы из `input.txt`.
- Остаётся наблюдать за закачкой и ждать её завершения.

[![Скрипт за работой](image.png)](image.png)

***
#### Надеюсь этот скрипт Вам пригодится!
