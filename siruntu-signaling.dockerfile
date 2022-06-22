FROM node:16.15-alpine

WORKDIR /app

COPY package*.json ./
COPY server.js ./

RUN npm install

CMD [ "node", "server.js" ]