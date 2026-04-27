# Capulink
 Aplicación web para gardar ligazóns

# Comezar
Clona o repositorio
```
git clone https://github.com/manuelcpdev/capulink.git
cd capulink
```

## Frontend
Instala as dependencias
```
cd frontend
npm i
npm run ng serve
```

## Backend
```
cd backend
```
Crear un ficherio .env (a partires do .env.example) e configuralo
```
composer install
php artisan key:generate
php artisan migrate
```

# Tecnoloxías empregadas
Frontend: Angular, TypeScript, Tailwind CSS

Backend: Laravel Sanctum, MySQL


