# Cloud&#64;Mail.Ru Downloader
[![GitHub Releases](https://img.shields.io/github/downloads/Geograph-us/Cloud-Mail.Ru-Downloader/total.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/releases/latest)
[![GitHub issues](https://img.shields.io/github/issues/Geograph-us/Cloud-Mail.Ru-Downloader.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/Geograph-us/Cloud-Mail.Ru-Downloader.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/pulls)

Многопоточное скачивание из облака [Mail.Ru](http://cloud.mail.ru/) по публичной ссылки. Авторизация в Mail.Ru не требуется.

- Скрипт консольный, написан на PHP.
- Для скачивания используется консольный загрузчик [Aria2c](https://aria2.github.io/).
- Скрипт умеет корректно обрабатывать папки в облаке любой вложенности.
- Поддерживается докачка файлов.
- Для работы скрипта нужно установить php на компьютер, например отсюда http://windows.php.net/download/ (если уже установлен какой-нибудь Веб-сервер, например, [Denwer](http://www.denwer.ru/) или [OpenServer](http://open-server.ru/), то php от него тоже подойдет).
- Скрипт работает в PHP версий *5.x.x-7.2.x*.

## Порядок работы

- Скачать релиз скрипта, в который уже включена минимальная версия php
- В файл `links.txt` записать публичные ссылки на скачивание с облака вида https://cloud.mail.ru/public/9bFs/gVzxjU5uC по одной на строку.
- Запустить `start.bat`
- Скрипт сформирует файл с прямыми ссылками на скачивание `input.txt`.
- После чего запустится Aria2c Downloader, который скачает файлы из `input.txt`.
- Остаётся наблюдать за закачкой и ждать её завершения. Скачанные файлы окажутся в папке `downloads`.

[![Скрипт за работой](image.png)](image.png)

## Настройка PHP, если используете уже установленный
В `php.ini` должно быть активировано openssl-расширение:
>extension_dir = "ext"\
>extension=php_openssl.dll

### Видео-пример:
[![Cloud.MailRu.Downloader Video example](https://img.youtube.com/vi/WnJyXEdEqfI/0.jpg)](https://www.youtube.com/watch?v=WnJyXEdEqfI)

***
#### Надеюсь этот скрипт Вам пригодится!
