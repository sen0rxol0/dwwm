// $ node server.js
const http = require('http');
const fs = require('fs');
const path = require('path');
const PORT = 8080;

const server = http.createServer((req, res) =>  {
  if (req.url === '/') {
    fs.readFile(path.join(__dirname, 'index.html'), (err, data) => {
      res.setHeader('Content-Type', 'text/html');
      res.writeHead(200);
      if (err) throw err;
      res.end(data);
    });
  } else if (req.url.endsWith('.js')) {
    fs.readFile(path.join(__dirname, req.url), (err, data) => {
      res.setHeader('Content-Type', 'text/javascript');
      res.writeHead(200);
      if (err) throw err;
      res.end(data);
    });
  } else {
    res.writeHead(404);
    res.end('NOT FOUND!');
  }
});

console.log(__dirname); // chemin du repertoire actuel

server.listen(PORT, () => {
  console.log(`Serveur écoute à la porte ${PORT} sur http://localhost:${PORT}`);
});
