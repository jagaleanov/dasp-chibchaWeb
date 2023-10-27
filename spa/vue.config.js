const { defineConfig } = require('@vue/cli-service');

const domain = 'chibchaweb.com'
const port = 8080

module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    host: domain,
    port: port,
    https: { key: './certs/' + domain + '/' + domain + '.key', cert: './certs/' + domain + '/' + domain + '.crt' },
  }
});
