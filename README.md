# Hansmega - Convection Sewing Recorder

This is a project about web application to monitor and record every production of sewing to know output every a hour

## Support me

<a href="https://trakteer.id/ajikamaludin" target="_blank"><img id="wse-buttons-preview" src="https://cdn.trakteer.id/images/embed/trbtn-blue-2.png" height="40" style="border:0px;height:40px;" alt="Trakteer Saya"></a>

## Requirements

-   PHP 8.1 or latest
-   Node 16+ or latest

## How to run

```bash
cp .env.example .env # configure app for laravel
touch database/database.sqlite # if you use .env.example with default sqlite database
composer install
npm install
npm run dev # compiling asset for development
```

## Default User

```bash
username : admin@admin.com
password : password
```

## Compile Assets ( to prod )

```bash
npm run build
```

## Screenshot

![image1](1.png?raw=true)
![image2](2.png?raw=true)

## Rsync

```bash
rsync -arP -e 'ssh -p 222' --exclude=node_modules --exclude=.git --exclude=.env --exclude=storage --exclude=public/hot . pi@ajikamaludin.id:/home/pi/projects/konveksi
```
