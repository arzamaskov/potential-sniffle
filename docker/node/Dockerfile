FROM node:24-alpine

ARG APP_UID=1000
ARG APP_GID=1000

ENV PNPM_HOME=/pnpm
ENV PATH="$PNPM_HOME:$PATH"
ENV HOME=/tmp/node-home

RUN apk add --no-cache su-exec \
    && corepack enable \
    && corepack prepare pnpm@latest --activate \
    && pnpm config set store-dir /pnpm/store \
    && mkdir -p /pnpm/store /tmp/node-home \
    && chown -R "${APP_UID}:${APP_GID}" /pnpm /tmp/node-home

WORKDIR /var/www/html

USER ${APP_UID}:${APP_GID}

CMD ["sh"]
