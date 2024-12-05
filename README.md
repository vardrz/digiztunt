# Digizstunt

## Requirement

-   PHP `>= v7.4`
-   MySQL / MariaDB

## Setup Project
1 - Clone Project
```
Clone atau Download source code ini
```

2 - Install composser package

```bash
composer install
```

3 - Copy .env file

```bash
cp .env.example .env
```

4 - Generate Artisan Key

```bash
php artisan key:generate
```

5 - Setup Database & Run Migration

```bash
php artisan migrate --seed
```

### Another Desc
** Generate Kader Posyandu
```bash
akses ke {url}/kader/generate

otomatis akan membuat data 98 kader posyandu dengan akses:

username: {namakelurahan}-{namaposyandu}
          contoh: padukuhankraton-mawar3

password: 123456
```
