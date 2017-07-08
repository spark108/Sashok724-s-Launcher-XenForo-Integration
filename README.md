# Sashok724-s-Launcher-XenForo-Integration
## Описание
Данный скрипт позволяет произвести авторизацию в лаунчере через CMS XenForo.
## Что добавлено?
1. Пользователь не сможет авторизоватся если:<br>
1.1. Если пользователь не подтвердил свой E-Mail.<br>
1.2. Если пользователь бал забанен.
## Установка
1. Положить скрипт в корень сайта где находится папка `library`. <br>
2. Указать в настройках LaunchServer'а следующий провайдер:<br>
```
authProvider: "request";
authProviderConfig: {
	url: "http://website.ru/launcher.php?login=%login%&password=%password%";
	response: "OK:(?<username>.+)";
};
```
Где `website.ru` указывается ваш домен.
