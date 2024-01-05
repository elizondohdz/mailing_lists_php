# API Documentation

This a simple Api made in PHP using Laravel Framework, following SOLID principles, Laravel conventions and PSR standards.

## Set Key

> $ php artisan key:generate

## Run migrations and seed

Everything needed is inside the project

> $ php artisan migrate --seed

This project has seeders for the tables so it can be ready out of the box.

## Endpoints

> $ php artisan route:list

Following Laravel conventions

GET       Retrieves items (or item if specified) <br>
POST      Create a new record <br>
PUT       Updates record <br>
DELETE    Deletes record <br>

## Testing

There are some Feature/Unit tests to ensure the quality of the project.

> php artisan test

## To Do List

Authentication
Policies and permissions
Scheduled tasks

These features are not within the scope of this project, but are addressed in this project:

https://github.com/elizondohdz/task-management