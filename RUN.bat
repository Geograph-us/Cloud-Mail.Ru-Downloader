:: Add your cloud-mail links to links.txt than run RUN.bat

@echo off
@chcp 1251>nul
"%~dp0\php\php.exe" "%~dp0\cloud_mail_downloader.php"
pause
