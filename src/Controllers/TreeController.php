<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Controllers;

use AlexiDg\InventorySystem\Http\Response;
use AlexiDg\InventorySystem\Views\View;

final class TreeController
{
    public function index(): Response
    {
        // Definimos el árbol usando Nowdoc para mantener el formato limpio
        $tree = <<<'TREE'
├── api
│   └── index.php
├── composer.json
├── composer.lock
├── docker-compose.yml
├── Dockerfile
├── Dockerfile.vercel
├── docs
│   ├── apache-local.conf.example
│   ├── init.md
│   ├── manual-edicion.md
│   ├── PRD-vercel-y-acceso-local.md
│   ├── PRD-video-guia-hyperframes.md
│   ├── project-description.md
│   └── vercel-deployment.md
├── index.php
├── public
│   └── index.php
├── README.md
├── src
│   ├── Config
│   │   └── Database.php
│   ├── Controllers
│   │   ├── HealthController.php
│   │   ├── HomeController.php
│   │   └── TreeController.php
│   ├── Http
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Router.php
│   └── Views
│       └── View.php
├── vendor
│   ├── autoload.php
│   ├── composer
│   │   ├── autoload_classmap.php
│   │   ├── autoload_files.php
│   │   ├── autoload_namespaces.php
│   │   ├── autoload_psr4.php
│   │   ├── autoload_real.php
│   │   ├── autoload_static.php
│   │   ├── ClassLoader.php
│   │   ├── installed.json
│   │   ├── installed.php
│   │   ├── InstalledVersions.php
│   │   ├── LICENSE
│   │   └── platform_check.php
│   ├── graham-campbell
│   │   └── result-type
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       └── src
│   │           ├── Error.php
│   │           ├── Result.php
│   │           └── Success.php
│   ├── phpoption
│   │   └── phpoption
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       └── src
│   │           └── PhpOption
│   │               ├── LazyOption.php
│   │               ├── None.php
│   │               ├── Option.php
│   │               └── Some.php
│   ├── symfony
│   │   ├── polyfill-ctype
│   │   │   ├── bootstrap80.php
│   │   │   ├── bootstrap.php
│   │   │   ├── composer.json
│   │   │   ├── Ctype.php
│   │   │   ├── LICENSE
│   │   │   └── README.md
│   │   ├── polyfill-mbstring
│   │   │   ├── bootstrap72.php
│   │   │   ├── bootstrap80.php
│   │   │   ├── bootstrap.php
│   │   │   ├── composer.json
│   │   │   ├── LICENSE
│   │   │   ├── Mbstring.php
│   │   │   ├── README.md
│   │   │   └── Resources
│   │   │       └── unidata
│   │   │           ├── caseFolding.php
│   │   │           ├── lowerCase.php
│   │   │           ├── titleCaseRegexp.php
│   │   │           └── upperCase.php
│   │   └── polyfill-php80
│   │       ├── bootstrap.php
│   │       ├── composer.json
│   │       ├── LICENSE
│   │       ├── Php80.php
│   │       ├── PhpToken.php
│   │       ├── README.md
│   │       └── Resources
│   │           └── stubs
│   │               ├── Attribute.php
│   │               ├── PhpToken.php
│   │               ├── Stringable.php
│   │               ├── UnhandledMatchError.php
│   │               └── ValueError.php
│   └── vlucas
│       └── phpdotenv
│           ├── composer.json
│           ├── LICENSE
│           └── src
│               ├── Dotenv.php
│               ├── Exception
│               │   ├── ExceptionInterface.php
│               │   ├── InvalidEncodingException.php
│               │   ├── InvalidFileException.php
│               │   ├── InvalidPathException.php
│               │   └── ValidationException.php
│               ├── Loader
│               │   ├── LoaderInterface.php
│               │   ├── Loader.php
│               │   └── Resolver.php
│               ├── Parser
│               │   ├── EntryParser.php
│               │   ├── Entry.php
│               │   ├── Lexer.php
│               │   ├── Lines.php
│               │   ├── ParserInterface.php
│               │   ├── Parser.php
│               │   └── Value.php
│               ├── Repository
│               │   ├── Adapter
│               │   │   ├── AdapterInterface.php
│               │   │   ├── ApacheAdapter.php
│               │   │   ├── ArrayAdapter.php
│               │   │   ├── EnvConstAdapter.php
│               │   │   ├── GuardedWriter.php
│               │   │   ├── ImmutableWriter.php
│               │   │   ├── MultiReader.php
│               │   │   ├── MultiWriter.php
│               │   │   ├── PutenvAdapter.php
│               │   │   ├── ReaderInterface.php
│               │   │   ├── ReplacingWriter.php
│               │   │   ├── ServerConstAdapter.php
│               │   │   └── WriterInterface.php
│               │   ├── AdapterRepository.php
│               │   ├── RepositoryBuilder.php
│               │   └── RepositoryInterface.php
│               ├── Store
│               │   ├── File
│               │   │   ├── Paths.php
│               │   │   └── Reader.php
│               │   ├── FileStore.php
│               │   ├── StoreBuilder.php
│               │   ├── StoreInterface.php
│               │   └── StringStore.php
│               ├── Util
│               │   ├── Regex.php
│               │   └── Str.php
│               └── Validator.php
├── vercel.json
├── video-guide
│   ├── audio
│   │   ├── narration.txt
│   │   ├── narration.wav
│   │   └── transcript.json
│   ├── DESIGN.md
│   ├── index.html
│   ├── README.md
│   ├── renders
│   │   └── inventory-system-guide.mp4
│   ├── SCRIPT.md
│   └── STORYBOARD.md
└── views
TREE;

        return Response::json([
            "status" => "ok",
            "service" => 'inventory-system',
            'tree' => $tree
        ]);
    }

    public function html(): Response
    {
        return Response::html(View::render('tree-html', [
            'appName' => 'Pagina de prueba',
        ]));
    }
}
