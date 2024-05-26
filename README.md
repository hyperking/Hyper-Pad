# Hyper-Pad

Flat file cms written in php

# Create Docker image

`docker build -f php-server -t {your-image-name} .`

# Mount docker volumes into container

`
docker run -d -p 8080:80 \
  -v ./xdebug.ini:/usr/local/etc/php/conf.d/xdebug.ini\
  -v /apps/demo_site:/demo_site \
  {your-image-name}
`