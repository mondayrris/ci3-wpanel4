# CI3-WPANEL4

> [Original README](./readme-original.md)

## Todo

- [ ] Gallery max post size is 2MB
- [ ] Banner photo (no banner image, banner image height is high)
- [ ] Fix on page link break, missing lang message
- [x] Code convention, fix, refactorization

## Configuration

Change $config['base_url'] in `app\config\config.php`

Copy `app/database/init/development.sqlite` to `app/database/development.sqlite`

Copy `app/database/init/production.sqlite` to `app/database/production.sqlite`

## Setup on your own

Use [DB Browser](https://sqlitebrowser.org) to create these two sqlite files

`composer run dev`

Navigate [http://localhost:8080/migrate](http://localhost:8080/migrate)

## Run

`composer run dev`

Navigate [http://localhost:8080/index.php/setup](http://localhost:8080/index.php/setup)

Navigate [http://localhost:8080](http://localhost:8080)

Navigate [http://localhost:8080/index.php/admin](http://localhost:8080/index.php/admin)

## Demo

Navigate [https://ci3-wpanel4.mondayrris.site](https://ci3-wpanel4.mondayrris.site)

## License

MIT