# ---- Build stage: static site ----
FROM node:22-alpine AS build
WORKDIR /workspace

COPY docs/package.json docs/package-lock.json /workspace/docs/
RUN cd /workspace/docs && npm ci

COPY docs /workspace/docs
COPY src /workspace/src

RUN cd /workspace/docs \
    && npm run generate:commands \
    && npm run build

# ---- Serve stage ----
FROM nginx:alpine AS serve
COPY docker/docs/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /workspace/docs/dist /usr/share/nginx/html
EXPOSE 80
