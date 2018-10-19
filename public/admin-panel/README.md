## Установка

 - Клонируем репозиторий в public (там где index.php) директорию проекта
 - `composer update`
 
## Авторизация

 - создаем AuthUserFile `htpasswd -c .pwd username`
 - добавляем пользователей `htpasswd .pwd username`

## Настройка

Все что нужно, находится в файле `config.yml`

* workingDirectory - рабочая директория для выполняния команд
* defaultTab - активная вкладка по умолчанию
* tabs - вкладки
  * grid - разметка (bootstrap 3)
  * commands - доступные команды `comamnd-key: command`
   * command - команда
   * description - описание команды

Grid

![grid](https://d1ro8r1rbfn3jf.cloudfront.net/ms_167796/YSZxL8O4gN4Cvcwn9LSc1e8kT0wrex/%25D0%25A1%25D0%25B5%25D1%2580%25D0%25B2%25D0%25B8%25D1%2581%25D0%25BD%25D0%25B0%25D1%258F%2B%25D0%25BF%25D0%25B0%25D0%25BD%25D0%25B5%25D0%25BB%25D1%258C%2B2017-12-26%2B15-09-51.png?Expires=1514380344&Signature=NMwV~4G3Kthx~xCqZ2dVIK3BI4LzJxhS8FerOJENywuYLMpAjgP53pmc3KnFGj6kDmU-ELPO8i1yG2esDpTtYgHVkZuv7ULEdUhLD-aQK5luhyODVEeNq1hHxCZ5nb--kslzCkttYxKdsmdTlPfR1iegcC3~FZf0sZUc1-j37YSI2Tbh6xtBUULCIwTSdlX5uDfbIKzu-XHjrv9mgcffYRJ9lcXF3krlVMRsEuu-IFMVWgK8WQgvciLYBAI~8nByu~0kLbv5nSsXVLzf9wsofuXS5MCK-lCwFeMtP7V4gBnkwrXRp1Nl~~YyiSACOg2DWvWEeSgcLLonEG3RXjaRwA__&Key-Pair-Id=APKAJHEJJBIZWFB73RSA)

Все что не вошло в этот список, просто набор параметров.

Пример.

```yml
# required
workingDirectory: /var/www/html/laravel-app

# params
queue:
    tries: 1

# required
tabs:

    artisan:
        grid:
            rows:
                -
                    - "col-md-12" # index 0 (row 1)
                -
                    - "col-md-6" # index 1 (row 2)
                    - "col-md-6" # index 2 (row 2)
        commands:
            # index 0
            routes: 
                command: php artisan route:list
                description: описание команды
            # index 1
            storage link: php artisan storage:link
            # index 2
            queue work: php artisan queue:work --once --tries ${queue.tries}

```

