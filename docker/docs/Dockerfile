# ---- Build stage: static site ----
FROM node:22-alpine AS build
WORKDIR /workspace/docs

COPY docs/package.json docs/package-lock.json ./
RUN npm ci

COPY docs .
COPY src /workspace/src

RUN npm run generate:commands \
    && npm run build

# ---- Serve stage ----
FROM nginx:alpine AS serve
COPY docker/docs/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /workspace/docs/dist /usr/share/nginx/html
EXPOSE 80