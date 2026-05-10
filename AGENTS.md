# Repository Guidelines

## Project Structure & Module Organization

This is a Laravel 12 backend application. Core PHP code lives in `app/`: controllers in `app/Http/Controllers`, middleware in `app/Http/Middleware`, Eloquent models in `app/Models`, reusable traits in `app/Traits`, and response/transformer helpers in `app/Helpers`, `app/Models/Responders`, and `app/Transformers`.

Routes are split between `routes/api.php`, `routes/web.php`, and portal modules in `routes/portal/`. Database schema and seed data are in `database/migrations`, `database/seeders`, and `database/factories`. Frontend assets live in `resources/js`, `resources/css`, and `resources/views`; public entry assets live in `public/`. Tests are organized in `tests/Feature` and `tests/Unit`.

## Build, Test, and Development Commands

- `composer install`: install PHP dependencies.
- `npm install`: install Vite/Tailwind frontend tooling.
- `composer run setup`: install dependencies, create `.env`, generate the app key, migrate, and build assets.
- `composer run dev`: run the local server, queue listener, logs, and Vite together.
- `php artisan serve`: run only the Laravel HTTP server.
- `npm run dev`: start Vite for asset development.
- `npm run build`: build production frontend assets.
- `composer run test` or `php artisan test`: clear config and run the PHPUnit suite.
- `./vendor/bin/pint`: format PHP using Laravel Pint.

## Coding Style & Naming Conventions

Follow `.editorconfig`: UTF-8, LF line endings, 4-space indentation, final newline, and trimmed trailing whitespace. YAML files use 2 spaces. Format PHP with Pint before review.

Use PascalCase for PHP classes (`AgencyController`, `UserProfileTransformer`), camelCase for methods and variables, and snake_case for database columns. Keep route modules named for their domain, such as `routes/portal/users.php`.

## Testing Guidelines

PHPUnit is configured through `phpunit.xml`. Put HTTP and integration coverage in `tests/Feature`; put isolated business logic tests in `tests/Unit`. Name test files after the behavior or subject, ending in `Test.php`.

Run `composer run test` before opening a pull request. Add or update tests when changing controllers, middleware, authentication, permissions, migrations, or transformers.

## Commit & Pull Request Guidelines

Existing commits use short Spanish imperative summaries, for example `se agrega roles controller y models` and `manejo de rutas`. Keep commits concise and focused on one change.

Pull requests should include a brief description, affected endpoints or modules, migration/seed impact, test results, and relevant API examples. Link related issues when available and note required `.env` changes without committing secrets.

## Security & Configuration Tips

Do not commit `.env`, credentials, tokens, or generated logs. Keep authentication changes aligned with Laravel Sanctum/JWT configuration in `config/sanctum.php`, `config/jwt.php`, and `config/auth.php`.

## Migration & Seeder Rules

New domain tables must include `$table->softDeletes()` unless explicitly documented otherwise. Keep foreign keys ordered so referenced tables exist before constraints are added; if order prevents that, add the constraint in a later migration.

Current shared relationships use `application_user` for user-application assignments, `role_user` for user-role assignments, and `permission_role` for role permissions. Do not reintroduce `application_user_role`.

Seeders must be idempotent: prefer `updateOrCreate()`, `updateOrInsert()`, or `sync()` over raw `create()`/`attach()` when unique keys can already exist. After changing migrations or seeders, validate with `php artisan migrate:fresh --seed` when data reset is acceptable, or `php artisan migrate && php artisan db:seed` for additive changes.
