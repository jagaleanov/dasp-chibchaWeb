const { defineConfig } = require('@vue/cli-service');

const domain = 'chibchaweb.com'
const port = 8082

module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    host: domain,
    port: port,
    https: { key: './certs/' + domain + '.key', cert: './certs/' + domain + '.crt' },
  }
});
