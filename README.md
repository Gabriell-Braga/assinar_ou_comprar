# Instalação

https://stackoverflow.com/questions/65950888/laravel-8-project-is-not-opening-in-the-server-getting-error-in-testdatabases-p


```bash
npm install
composer install
php artisan key:generate
php artisan jwt:secret
php artisan storage:link
npm run dev
```

Caso obtenha o erro "JWT payload does not contain the required claims", acesse o arquivo config/jwt.php e comente o item 'required_claims.exp'

<br><br>

# Build
Ao concluir as alterações, executar ``npm run build`` e dar commit nos arquivos gerados.

<br><br>

# Links importantes da calculadora
Documentação da api Unidas <br>
https://livre.unidas.com.br/apilivre/endpoints/swagger/index.html

Design <br>
https://www.figma.com/file/q3Ioj0iIAXv09miUsSezDw/Unidas_Assinar-ou-comprar

Curva Anbima <br>
https://www.anbima.com.br/informacoes/est-termo/CZ.asp

Base comparação <br>
https://livre.unidas.com.br/compare


<br><br>

# Comanbdos completos Laranuxt

```bash
# *** Global ***
# development build with nuxt dev server with hot reloading
npm run dev
# production build
npm run build
# perform tests
npm run test
# find linting errors
npm run lint
# autofix linting errors
npm run lint:fix
# check for conflicting lint rules
npm run lint:check

# *** Laravel ***
# development build
npm run mix:dev
# development build with file watching
npm run mix:watch
# development build with file watching and polling
npm run mix:watch:poll
# development build with file watching, polling and hot reloading
npm run mix:hot
# production build
npm run mix:build
# perform tests (not implemented)
npm run mix:test

# *** Nuxt ***
# run dev server with hot reloading
npm run nuxt:dev
# production build
npm run nuxt:build
# perform tests
npm run nuxt:test

# *** Development ***
# cut a new release
npm run release
```

Bom dia Pessoal
Ontem estive implementando o endpoint /Simulation/all novo no sistema, e funcionou muito bem. Só que o problema, é que quando um carro dessa lista é selecionado eu preciso recuperar as informações completas do veículo selecionado com o /Cms/VehicleDetail passando period, franchise e slug como parâmetro, e alguns carros estão vindo sem os dados.

A criação desse endpoint é justamente pra não dar esse erro depois da lista de carros carregada.

Abaixo listei todos os carros que tive problema pra vocês colarem e testar no swagger:

Erros no endpoint
https://livre.unidas.com.br/api/Cms/vehicleDetail

{"period":48, "franchise":1000, "slug":"virtus-comfortline-200-tsi-at-1-0-4p"}
{"period":48, "franchise":1000, "slug":"nivus-highline-200-tsi-at-1-0-4p"}
{"period":48, "franchise":1000, "slug":"corolla-xei-vvt-ie-at-2-0-4p"}
{"period":48, "franchise":1000, "slug":"1450"}
{"period":48, "franchise":1000, "slug":"q3-prestige-plus-tfsi-flex-at-1-4-4p-2"}
{"period":48, "franchise":1000, "slug":"compass-limited-flex-4x2-at-2-0-4p"}
{"period":48, "franchise":1000, "slug":"compass-longitude-flex-4x2-at-2-0-4p"}
{"period":48, "franchise":1000, "slug":"virtus-comfortline-200-tsi-tech-ii-at-1-0-4p"}
{"period":48, "franchise":1000, "slug":"toro-freedom-diesel-at-4x4-2-0-4p"}
{"period":48, "franchise":1000, "slug":"onix-lt-mt-1-0-2"}
{"period":48, "franchise":1000, "slug":"toro-freedom-flex-at-4x2-1_8-4p"}
#   a s s i n a r _ o u _ c o m p r a r  
 