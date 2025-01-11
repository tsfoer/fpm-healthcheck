# fpm-healthcheck

Requirements:
* box for creating PHAR https://github.com/box-project/box
* composer https://getcomposer.org/
* https://github.com/willfarrell/docker-autoheal in combination with the healthcheck
## Build

```bash
cd src
box compile
```

## Usage

Use with php-fpm docker image and compose.yaml
    
```yaml
services:
  fpm:
    image: "php:8.2.11-fpm-alpine3.18"
    ports:
      - "9000:9000"
    networks:
      - local
    volumes:
      - ../bin/fpm-checker:/usr/local/bin/fpm-checker:ro
    labels:
      autoheal: true
    healthcheck:
      test: ["CMD-SHELL", "/usr/local/bin/fpm-checker || exit 1"]
      interval: 30s
      timeout: 5s
      retries: 2
      start_period: 5s

  autoheal:
    environment:
      AUTOHEAL_CONTAINER_LABEL: autoheal
      AUTOHEAL_INTERVAL: 10
      AUTOHEAL_START_PERIOD: 5
      AUTOHEAL_ONLY_MONITOR_RUNNING: true
      CURL_TIMEOUT: 20
    image: willfarrell/autoheal:1.2.0
    network_mode: none
    restart: unless-stopped
    volumes:
      - /etc/localtime:/etc/localtime:ro
      - /var/run/docker.sock:/var/run/docker.sock:rw
```