FROM node:24-alpine

ENV PNPM_HOME=/pnpm
ENV PATH="$PNPM_HOME:$PATH"

RUN corepack enable \
    && corepack prepare pnpm@latest --activate \
    && pnpm config set store-dir /pnpm/store

WORKDIR /var/www/html

CMD ["sh"]
