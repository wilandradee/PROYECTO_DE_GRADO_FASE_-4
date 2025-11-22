# PROYECTO_DE_GRADO_FASE_-4

Repositorio público adaptado para el proyecto de grado (Fase 4).

**Contenido**
- backend_scaffold/: modelos, controladores, migraciones y rutas para integrar con una app Laravel.
- frontend/: scaffold React (componentes principales).
- modelo-predictivo/: scripts para entrenar/predictor.
- docker-compose.yml, Dockerfile (backend), nginx config.
- scripts para bootstrap y archivo `.env.example`.

## Cómo usar

1. Descomprime el repositorio y sigue las instrucciones en `README.md` (o ejecuta `backend/bootstrap.sh`) para crear la app Laravel.
2. Configura `.env` con las credenciales y ejecuta migraciones.
3. Levanta contenedores con `docker-compose up --build`.

## Notas
Este repo es una base para desarrollar. Requiere personalización (auth, validaciones, tests, CI/CD).
