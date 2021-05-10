# Cloud&#64;Mail.Ru Downloader
[![GitHub Releases](https://img.shields.io/github/downloads/Geograph-us/Cloud-Mail.Ru-Downloader/total.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/releases/latest)
[![GitHub issues](https://img.shields.io/github/issues/Geograph-us/Cloud-Mail.Ru-Downloader.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/Geograph-us/Cloud-Mail.Ru-Downloader.svg?maxAge=60&style=flat-square)](https://github.com/Geograph-us/Cloud-Mail.Ru-Downloader/pulls)

Многопоточное скачивание из облака [Mail.Ru](http://cloud.mail.ru/) по публичной ссылки. Авторизация в Mail.Ru не требуется.

- Скрипт консольный, написан на PHP.
- Для скачивания используется консольный загрузчик [Aria2c](https://aria2.github.io/).
- Скрипт умеет корректно обрабатывать папки в облаке любой вложенности.
- Поддерживается докачка файлов.

## Порядок работы

- Скачать релиз скрипта, в который уже включена минимальная версия php
- В файл `links.txt` записать публичные ссылки на скачивание с облака вида https://cloud.mail.ru/public/9bFs/gVzxjU5uC по одной на строку.
- Запустить `RUN.bat`
- Скрипт сформирует файл с прямыми ссылками на скачивание `input.txt`.
- После чего запустится Aria2c Downloader, который скачает файлы из `input.txt`.
- Остаётся наблюдать за закачкой и ждать её завершения. Скачанные файлы окажутся в папке `downloads`.

[![Скрипт за работой](image.png)](image.png)

***
#### Надеюсь этот скрипт Вам пригодится!
