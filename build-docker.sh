#!/bin/bash

## Script com a intenção de facilitar os testes da criação do container. As informações seneíveis contidas abaixo, não são refetidas em ambvientes de homologação e produção.

docker build -f DockerfileProduction \
    --build-arg APP_KEY='base64:6wDEYD4ADQo6Y+Hdrjem+kSsvEGJRSaKwRsD34kLFXA=' \
    --build-arg REDIS_HOST='redis-assinar-comprar' \
    --build-arg REDIS_PASSWORD='Senha123#' \
    --build-arg REDIS_PORT=6379 \
    --build-arg APP_ENV=production \
    --build-arg APP_DEBUG=false \
    -t assinar-comprar:hml .


docker run -it --rm -p "80:80" --network=docker_app-assinar-comprar --name=assinar-comprar-prod assinar-comprar:hml

