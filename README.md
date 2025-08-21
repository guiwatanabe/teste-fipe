# Teste - FIPE
[![Laravel - Tests](https://github.com/guiwatanabe/teste-fipe/actions/workflows/laravel.yml/badge.svg)](https://github.com/guiwatanabe/teste-fipe/actions/workflows/laravel.yml)

## Executando a aplicação - Docker
- Copiar .env.example e configurar variáveis de ambiente

- ```docker compose up -d --build```

- ```docker-compose run --rm composer install```

- ```docker-compose run --rm artisan migrate```

- ```docker-compose run --rm artisan db:seed DatabaseSeeder``` - criar usuário teste

## Executando localmente
- Copiar .env.example e configurar variáveis de ambiente

- ```composer install```

- ```php artisan migrate```

- ```php artisan db:seed DatabaseSeeder``` - criar usuário teste

- ```composer run dev```

## Testes

### Pest
 
 ```composer run test```

### PHPStan
 
 ```composer run phpstan```