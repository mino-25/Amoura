# --- Développement (docker-compose local) ---
FROM node:20-alpine AS dev
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]

# --- Build de production ---
FROM node:20-alpine AS build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
# Vite inline les variables VITE_* dans le bundle AU MOMENT DU BUILD, contrairement
# aux variables backend lues au runtime : il faut donc les passer en argument de build
# (--build-arg ou build.args dans docker-compose.prod.yml), pas en "environment:".
ARG VITE_API_URL
ENV VITE_API_URL=${VITE_API_URL}
RUN npm run build

# --- Production : fichiers statiques servis par Nginx ---
FROM nginx:alpine AS production
COPY --from=build /app/dist /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
