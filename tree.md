
│  .gitignore
│  .htaccess
│  .travis.yml
│  1.txt
│  404.html
│  build.php
│  composer.json  ------------------------------安装配置文件
│  composer.lock  ------------------------------
│  index.html
│  LICENSE.txt
│  README.md   ---------------------------------读我
│  think
│  vue-admin.sql   ------------------------------数据库文件
│  
├─application   ---------------------------------APP目录
│  │  .htaccess
│  │  command.php
│  │  common.php
│  │  tags.php
│  │  
│  ├─admin      -------------------------------后台模块
│  │  │  common.php
│  │  │  tags.php
│  │  │  
│  │  ├─behavior ------------------------------原作者的登录验证
│  │  │      CheckAuth.php
│  │  │      
│  │  ├─controller ------------------------------存放所有接口方法
│  │  │  │  Base.php  ---------------------------基类
│  │  │  │  BaseCheckUser.php  ------------------用户登录钩子(admin不使用jwt,使用作者原框架token验证)
│  │  │  │  EntranceController.php --------------后台入口路由
│  │  │  │  
│  │  │  ├─ad  ---------------------------------原框架默认广告模块
│  │  │  │      AdController.php
│  │  │  │      SiteController.php
│  │  │  │      
│  │  │  ├─auth --------------------------------原框架后台相关的权限、登录
│  │  │  │      AdminController.php
│  │  │  │      LoginController.php
│  │  │  │      PermissionRuleController.php
│  │  │  │      RoleController.php
│  │  │  │      
│  │  │  ├─file --------------------------------原框架后台相关的上传
│  │  │  │      ResourceController.php
│  │  │  │      ResourceTagController.php
│  │  │  │      UploadController.php
│  │  │  │      
│  │  │  ├─job  --------------------------------后台队列任务(不同任务分别不同.php)
│  │  │  │      Demo.php
│  │  │  │      
│  │  │  └─source --------------------------------溯源相关
│  │  │          SourcecodeController.php
│  │  │          
│  │  ├─model  --------------------------------后台模型层
│  │  │      FileResource.php
│  │  │      FileResourceTag.php
│  │  │      
│  │  └─view  --------------------------------视图层
│  │          .gitignore
│  │          
│  ├─common  ------------------------------公共方法(jwt、返回json类、异常类、公共模型、)
│  │  ├─auth  ---------------------------jwt
│  │  │      JwtAuthWap.php
│  │  │      
│  │  ├─constant
│  │  │      CacheKeyConstant.php
│  │  │      
│  │  ├─enums  -----------------------全局无需验证路由、全局错误返回码
│  │  │      ErrorCode.php
│  │  │      RouteNo.php
│  │  │      
│  │  ├─exception  -------------------全局异常类
│  │  │      exception.html
│  │  │      GlobalHandle.php
│  │  │      JsonException.php
│  │  │      l404.html
│  │  │      
│  │  ├─model
│  │  │  ├─ad
│  │  │  │      Ad.php
│  │  │  │      AdSite.php
│  │  │  │      
│  │  │  └─auth
│  │  │          AuthAdmin.php
│  │  │          AuthPermission.php
│  │  │          AuthPermissionRule.php
│  │  │          AuthRole.php
│  │  │          AuthRoleAdmin.php
│  │  │          
│  │  ├─utils   ----------------------存放各种Diy工具类
│  │  │      PassWordUtils.php
│  │  │      PublicFileUtils.php
│  │  │      RedisUtils.php
│  │  │      TokenUtils.php
│  │  │      TreeUtils.php
│  │  │      UrlUtils.php
│  │  │      WechatUtils.php
│  │  │      WeDbUtils.php
│  │  │      
│  │  └─vo     ----------------------json封装方法
│  │          ResultVo.php
│  │          
│  ├─http
│  │  └─middleware   ----------------中间件
│  │          JwtApi.php
│  │          
│  ├─index
│  │  └─controller
│  │          Index.php
│  │          
│  └─wap           ---------------------前端所有接口方法
│      │  common.php
│      │  tags.php
│      │  
│      ├─controller  ---------------------控制器
│      │  │  Base.php ---------------------基类
│      │  │  BaseCheckUser.php ------------钩子(弃用保留)
│      │  │  EntranceController.php -------入口Diy路由
│      │  │  
│      │  ├─auth   -----------------------遗留的
│      │  │      AdminController.php
│      │  │      LoginController.php
│      │  │      PermissionRuleController.php
│      │  │      RoleController.php
│      │  │      
│      │  ├─business --------------------商家
│      │  │      BusinessController.php
│      │  │      
│      │  ├─file ------------------------文件
│      │  │      ResourceController.php
│      │  │      ResourceTagController.php
│      │  │      UploadController.php
│      │  │      
│      │  └─user ------------------------用户
│      │          UserController.php
│      │          
│      ├─model
│      │      FileResource.php
│      │      FileResourceTag.php
│      │      
│      └─view
│              .gitignore
│              
├─config  -----------------------配置文件
│      app.php
│      cache.php
│      cookie.php
│      database.php
│      gateway_worker.php
│      log.php
│      public_file.php
│      queue.php
│      session.php
│      template.php
│      trace.php
│      wechat.php
│      wechattpl.php
│      worker.php
│      worker_server.php
│      
├─extend  -----------------------拓展类
│      .gitignore
│      
├─public
│  │  .htaccess
│  │  favicon.ico
│  │  index.php
│  │  nginx.htaccess
│  │  robots.txt
│  │  router.php
│  │  
│  ├─static
│  │      .gitignore
│  │      
│  └─uploads
│          .gitignore
│          
├─route          --------------------主路由
│      route.php
│      
├─runtime
│  │  .gitignore
│  │  
│  ├─cache
│  │  └─50
│  │          03ed13d1627f34b9f621aa8697e068.php
│  │          
│  ├─log
│  │          
│  └─temp
│          4c1d29f264ecb60ffe56794290e59e53.php
│          
├─thinkphp
│  │  .gitignore
│  │  .htaccess
│  │  base.php
│  │  composer.json
│  │  CONTRIBUTING.md
│  │  convention.php
│  │  helper.php
│  │  LICENSE.txt
│  │  logo.png
│  │  phpunit.xml.dist
│  │  README.md
│  │  
│  ├─lang
│  │      zh-cn.php
│  │      
│  ├─library
│  │  ├─think
│  │  │  │  App.php
│  │  │  │  Build.php
│  │  │  │  Cache.php
│  │  │  │  Collection.php
│  │  │  │  Config.php
│  │  │  │  Console.php
│  │  │  │  Container.php
│  │  │  │  Controller.php
│  │  │  │  Cookie.php
│  │  │  │  Db.php
│  │  │  │  Debug.php
│  │  │  │  Env.php
│  │  │  │  Error.php
│  │  │  │  Exception.php
│  │  │  │  Facade.php
│  │  │  │  File.php
│  │  │  │  Hook.php
│  │  │  │  Lang.php
│  │  │  │  Loader.php
│  │  │  │  Log.php
│  │  │  │  Middleware.php
│  │  │  │  Model.php
│  │  │  │  Paginator.php
│  │  │  │  Process.php
│  │  │  │  Request.php
│  │  │  │  Response.php
│  │  │  │  Route.php
│  │  │  │  Session.php
│  │  │  │  Template.php
│  │  │  │  Url.php
│  │  │  │  Validate.php
│  │  │  │  View.php
│  │  │  │  
│  │  │  ├─cache
│  │  │  │  │  Driver.php
│  │  │  │  │  
│  │  │  │  └─driver
│  │  │  │          File.php
│  │  │  │          Lite.php
│  │  │  │          Memcache.php
│  │  │  │          Memcached.php
│  │  │  │          Redis.php
│  │  │  │          Sqlite.php
│  │  │  │          Wincache.php
│  │  │  │          Xcache.php
│  │  │  │          
│  │  │  ├─config
│  │  │  │  └─driver
│  │  │  │          Ini.php
│  │  │  │          Json.php
│  │  │  │          Xml.php
│  │  │  │          
│  │  │  ├─console
│  │  │  │  │  Command.php
│  │  │  │  │  Input.php
│  │  │  │  │  LICENSE
│  │  │  │  │  Output.php
│  │  │  │  │  Table.php
│  │  │  │  │  
│  │  │  │  ├─bin
│  │  │  │  │      hiddeninput.exe
│  │  │  │  │      README.md
│  │  │  │  │      
│  │  │  │  ├─command
│  │  │  │  │  │  Build.php
│  │  │  │  │  │  Clear.php
│  │  │  │  │  │  Help.php
│  │  │  │  │  │  Lists.php
│  │  │  │  │  │  Make.php
│  │  │  │  │  │  RouteList.php
│  │  │  │  │  │  RunServer.php
│  │  │  │  │  │  Version.php
│  │  │  │  │  │  
│  │  │  │  │  ├─make
│  │  │  │  │  │  │  Command.php
│  │  │  │  │  │  │  Controller.php
│  │  │  │  │  │  │  Middleware.php
│  │  │  │  │  │  │  Model.php
│  │  │  │  │  │  │  Validate.php
│  │  │  │  │  │  │  
│  │  │  │  │  │  └─stubs
│  │  │  │  │  │          command.stub
│  │  │  │  │  │          controller.api.stub
│  │  │  │  │  │          controller.plain.stub
│  │  │  │  │  │          controller.stub
│  │  │  │  │  │          middleware.stub
│  │  │  │  │  │          model.stub
│  │  │  │  │  │          validate.stub
│  │  │  │  │  │          
│  │  │  │  │  └─optimize
│  │  │  │  │          Autoload.php
│  │  │  │  │          Config.php
│  │  │  │  │          Route.php
│  │  │  │  │          Schema.php
│  │  │  │  │          
│  │  │  │  ├─input
│  │  │  │  │      Argument.php
│  │  │  │  │      Definition.php
│  │  │  │  │      Option.php
│  │  │  │  │      
│  │  │  │  └─output
│  │  │  │      │  Ask.php
│  │  │  │      │  Descriptor.php
│  │  │  │      │  Formatter.php
│  │  │  │      │  Question.php
│  │  │  │      │  
│  │  │  │      ├─descriptor
│  │  │  │      │      Console.php
│  │  │  │      │      
│  │  │  │      ├─driver
│  │  │  │      │      Buffer.php
│  │  │  │      │      Console.php
│  │  │  │      │      Nothing.php
│  │  │  │      │      
│  │  │  │      ├─formatter
│  │  │  │      │      Stack.php
│  │  │  │      │      Style.php
│  │  │  │      │      
│  │  │  │      └─question
│  │  │  │              Choice.php
│  │  │  │              Confirmation.php
│  │  │  │              
│  │  │  ├─db
│  │  │  │  │  Builder.php
│  │  │  │  │  Connection.php
│  │  │  │  │  Expression.php
│  │  │  │  │  Query.php
│  │  │  │  │  Where.php
│  │  │  │  │  
│  │  │  │  ├─builder
│  │  │  │  │      Mysql.php
│  │  │  │  │      Pgsql.php
│  │  │  │  │      Sqlite.php
│  │  │  │  │      Sqlsrv.php
│  │  │  │  │      
│  │  │  │  ├─connector
│  │  │  │  │      Mysql.php
│  │  │  │  │      Pgsql.php
│  │  │  │  │      pgsql.sql
│  │  │  │  │      Sqlite.php
│  │  │  │  │      Sqlsrv.php
│  │  │  │  │      
│  │  │  │  └─exception
│  │  │  │          BindParamException.php
│  │  │  │          DataNotFoundException.php
│  │  │  │          ModelNotFoundException.php
│  │  │  │          
│  │  │  ├─debug
│  │  │  │      Console.php
│  │  │  │      Html.php
│  │  │  │      
│  │  │  ├─exception
│  │  │  │      ClassNotFoundException.php
│  │  │  │      DbException.php
│  │  │  │      ErrorException.php
│  │  │  │      Handle.php
│  │  │  │      HttpException.php
│  │  │  │      HttpResponseException.php
│  │  │  │      PDOException.php
│  │  │  │      RouteNotFoundException.php
│  │  │  │      TemplateNotFoundException.php
│  │  │  │      ThrowableError.php
│  │  │  │      ValidateException.php
│  │  │  │      
│  │  │  ├─facade
│  │  │  │      App.php
│  │  │  │      Build.php
│  │  │  │      Cache.php
│  │  │  │      Config.php
│  │  │  │      Cookie.php
│  │  │  │      Debug.php
│  │  │  │      Env.php
│  │  │  │      Hook.php
│  │  │  │      Lang.php
│  │  │  │      Log.php
│  │  │  │      Middleware.php
│  │  │  │      Request.php
│  │  │  │      Response.php
│  │  │  │      Route.php
│  │  │  │      Session.php
│  │  │  │      Template.php
│  │  │  │      Url.php
│  │  │  │      Validate.php
│  │  │  │      View.php
│  │  │  │      
│  │  │  ├─log
│  │  │  │  └─driver
│  │  │  │          File.php
│  │  │  │          Socket.php
│  │  │  │          
│  │  │  ├─model
│  │  │  │  │  Collection.php
│  │  │  │  │  Pivot.php
│  │  │  │  │  Relation.php
│  │  │  │  │  
│  │  │  │  ├─concern
│  │  │  │  │      Attribute.php
│  │  │  │  │      Conversion.php
│  │  │  │  │      ModelEvent.php
│  │  │  │  │      RelationShip.php
│  │  │  │  │      SoftDelete.php
│  │  │  │  │      TimeStamp.php
│  │  │  │  │      
│  │  │  │  └─relation
│  │  │  │          BelongsTo.php
│  │  │  │          BelongsToMany.php
│  │  │  │          HasMany.php
│  │  │  │          HasManyThrough.php
│  │  │  │          HasOne.php
│  │  │  │          MorphMany.php
│  │  │  │          MorphOne.php
│  │  │  │          MorphTo.php
│  │  │  │          OneToOne.php
│  │  │  │          
│  │  │  ├─paginator
│  │  │  │  └─driver
│  │  │  │          Bootstrap.php
│  │  │  │          
│  │  │  ├─process
│  │  │  │  │  Builder.php
│  │  │  │  │  Utils.php
│  │  │  │  │  
│  │  │  │  ├─exception
│  │  │  │  │      Faild.php
│  │  │  │  │      Failed.php
│  │  │  │  │      Timeout.php
│  │  │  │  │      
│  │  │  │  └─pipes
│  │  │  │          Pipes.php
│  │  │  │          Unix.php
│  │  │  │          Windows.php
│  │  │  │          
│  │  │  ├─response
│  │  │  │      Download.php
│  │  │  │      Json.php
│  │  │  │      Jsonp.php
│  │  │  │      Jump.php
│  │  │  │      Redirect.php
│  │  │  │      View.php
│  │  │  │      Xml.php
│  │  │  │      
│  │  │  ├─route
│  │  │  │  │  AliasRule.php
│  │  │  │  │  Dispatch.php
│  │  │  │  │  Domain.php
│  │  │  │  │  Resource.php
│  │  │  │  │  Rule.php
│  │  │  │  │  RuleGroup.php
│  │  │  │  │  RuleItem.php
│  │  │  │  │  RuleName.php
│  │  │  │  │  
│  │  │  │  └─dispatch
│  │  │  │          Callback.php
│  │  │  │          Controller.php
│  │  │  │          Module.php
│  │  │  │          Redirect.php
│  │  │  │          Response.php
│  │  │  │          Url.php
│  │  │  │          View.php
│  │  │  │          
│  │  │  ├─session
│  │  │  │  └─driver
│  │  │  │          Memcache.php
│  │  │  │          Memcached.php
│  │  │  │          Redis.php
│  │  │  │          
│  │  │  ├─template
│  │  │  │  │  TagLib.php
│  │  │  │  │  
│  │  │  │  ├─driver
│  │  │  │  │      File.php
│  │  │  │  │      
│  │  │  │  └─taglib
│  │  │  │          Cx.php
│  │  │  │          
│  │  │  ├─validate
│  │  │  │      ValidateRule.php
│  │  │  │      
│  │  │  └─view
│  │  │      └─driver
│  │  │              Php.php
│  │  │              Think.php
│  │  │              
│  │  └─traits
│  │      └─controller
│  │              Jump.php
│  │              
│  └─tpl
│          default_index.tpl
│          dispatch_jump.tpl
│          page_trace.tpl
│          think_exception.tpl
│          
└─vendor
    │  .gitignore
    │  autoload.php
    │  
    ├─composer
    │      autoload_classmap.php
    │      autoload_files.php
    │      autoload_namespaces.php
    │      autoload_psr4.php
    │      autoload_real.php
    │      autoload_static.php
    │      ClassLoader.php
    │      installed.json
    │      LICENSE
    │      
    ├─easywechat-composer
    │  └─easywechat-composer
    │      │  .gitignore
    │      │  .php_cs
    │      │  .travis.yml
    │      │  composer.json
    │      │  extensions.php
    │      │  LICENSE
    │      │  phpunit.xml
    │      │  README.md
    │      │  
    │      ├─src
    │      │  │  EasyWeChat.php
    │      │  │  Extension.php
    │      │  │  ManifestManager.php
    │      │  │  Plugin.php
    │      │  │  
    │      │  ├─Commands
    │      │  │      ExtensionsCommand.php
    │      │  │      Provider.php
    │      │  │      
    │      │  ├─Contracts
    │      │  │      Encrypter.php
    │      │  │      
    │      │  ├─Delegation
    │      │  │      DelegationOptions.php
    │      │  │      DelegationTo.php
    │      │  │      Hydrate.php
    │      │  │      
    │      │  ├─Encryption
    │      │  │      DefaultEncrypter.php
    │      │  │      
    │      │  ├─Exceptions
    │      │  │      DecryptException.php
    │      │  │      DelegationException.php
    │      │  │      EncryptException.php
    │      │  │      
    │      │  ├─Http
    │      │  │      DelegationResponse.php
    │      │  │      Response.php
    │      │  │      
    │      │  ├─Laravel
    │      │  │  │  config.php
    │      │  │  │  routes.php
    │      │  │  │  ServiceProvider.php
    │      │  │  │  
    │      │  │  └─Http
    │      │  │      └─Controllers
    │      │  │              DelegatesController.php
    │      │  │              
    │      │  └─Traits
    │      │          MakesHttpRequests.php
    │      │          WithAggregator.php
    │      │          
    │      └─tests
    │              ManifestManagerTest.php
    │              
    ├─firebase
    │  └─php-jwt
    │      │  composer.json
    │      │  LICENSE
    │      │  README.md
    │      │  
    │      └─src
    │              BeforeValidException.php
    │              ExpiredException.php
    │              JWK.php
    │              JWT.php
    │              SignatureInvalidException.php
    │              
    ├─guzzlehttp
    │  ├─guzzle
    │  │  │  .php_cs
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  Dockerfile
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  UPGRADING.md
    │  │  │  
    │  │  └─src
    │  │      │  Client.php
    │  │      │  ClientInterface.php
    │  │      │  functions.php
    │  │      │  functions_include.php
    │  │      │  HandlerStack.php
    │  │      │  MessageFormatter.php
    │  │      │  Middleware.php
    │  │      │  Pool.php
    │  │      │  PrepareBodyMiddleware.php
    │  │      │  RedirectMiddleware.php
    │  │      │  RequestOptions.php
    │  │      │  RetryMiddleware.php
    │  │      │  TransferStats.php
    │  │      │  UriTemplate.php
    │  │      │  Utils.php
    │  │      │  
    │  │      ├─Cookie
    │  │      │      CookieJar.php
    │  │      │      CookieJarInterface.php
    │  │      │      FileCookieJar.php
    │  │      │      SessionCookieJar.php
    │  │      │      SetCookie.php
    │  │      │      
    │  │      ├─Exception
    │  │      │      BadResponseException.php
    │  │      │      ClientException.php
    │  │      │      ConnectException.php
    │  │      │      GuzzleException.php
    │  │      │      InvalidArgumentException.php
    │  │      │      RequestException.php
    │  │      │      SeekException.php
    │  │      │      ServerException.php
    │  │      │      TooManyRedirectsException.php
    │  │      │      TransferException.php
    │  │      │      
    │  │      └─Handler
    │  │              CurlFactory.php
    │  │              CurlFactoryInterface.php
    │  │              CurlHandler.php
    │  │              CurlMultiHandler.php
    │  │              EasyHandle.php
    │  │              MockHandler.php
    │  │              Proxy.php
    │  │              StreamHandler.php
    │  │              
    │  ├─promises
    │  │  │  .php_cs.dist
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  Makefile
    │  │  │  phpstan-baseline.neon
    │  │  │  phpstan.neon.dist
    │  │  │  psalm.xml
    │  │  │  README.md
    │  │  │  
    │  │  └─src
    │  │          AggregateException.php
    │  │          CancellationException.php
    │  │          Coroutine.php
    │  │          Create.php
    │  │          Each.php
    │  │          EachPromise.php
    │  │          FulfilledPromise.php
    │  │          functions.php
    │  │          functions_include.php
    │  │          Is.php
    │  │          Promise.php
    │  │          PromiseInterface.php
    │  │          PromisorInterface.php
    │  │          RejectedPromise.php
    │  │          RejectionException.php
    │  │          TaskQueue.php
    │  │          TaskQueueInterface.php
    │  │          Utils.php
    │  │          
    │  └─psr7
    │      │  CHANGELOG.md
    │      │  composer.json
    │      │  LICENSE
    │      │  README.md
    │      │  
    │      └─src
    │              AppendStream.php
    │              BufferStream.php
    │              CachingStream.php
    │              DroppingStream.php
    │              FnStream.php
    │              functions.php
    │              functions_include.php
    │              Header.php
    │              InflateStream.php
    │              LazyOpenStream.php
    │              LimitStream.php
    │              Message.php
    │              MessageTrait.php
    │              MimeType.php
    │              MultipartStream.php
    │              NoSeekStream.php
    │              PumpStream.php
    │              Query.php
    │              Request.php
    │              Response.php
    │              Rfc7230.php
    │              ServerRequest.php
    │              Stream.php
    │              StreamDecoratorTrait.php
    │              StreamWrapper.php
    │              UploadedFile.php
    │              Uri.php
    │              UriNormalizer.php
    │              UriResolver.php
    │              Utils.php
    │              
    ├─monolog
    │  └─monolog
    │      │  CHANGELOG.md
    │      │  composer.json
    │      │  LICENSE
    │      │  phpstan.neon.dist
    │      │  README.md
    │      │  
    │      └─src
    │          └─Monolog
    │              │  ErrorHandler.php
    │              │  Logger.php
    │              │  Registry.php
    │              │  ResettableInterface.php
    │              │  SignalHandler.php
    │              │  Utils.php
    │              │  
    │              ├─Formatter
    │              │      ChromePHPFormatter.php
    │              │      ElasticaFormatter.php
    │              │      FlowdockFormatter.php
    │              │      FluentdFormatter.php
    │              │      FormatterInterface.php
    │              │      GelfMessageFormatter.php
    │              │      HtmlFormatter.php
    │              │      JsonFormatter.php
    │              │      LineFormatter.php
    │              │      LogglyFormatter.php
    │              │      LogstashFormatter.php
    │              │      MongoDBFormatter.php
    │              │      NormalizerFormatter.php
    │              │      ScalarFormatter.php
    │              │      WildfireFormatter.php
    │              │      
    │              ├─Handler
    │              │  │  AbstractHandler.php
    │              │  │  AbstractProcessingHandler.php
    │              │  │  AbstractSyslogHandler.php
    │              │  │  AmqpHandler.php
    │              │  │  BrowserConsoleHandler.php
    │              │  │  BufferHandler.php
    │              │  │  ChromePHPHandler.php
    │              │  │  CouchDBHandler.php
    │              │  │  CubeHandler.php
    │              │  │  DeduplicationHandler.php
    │              │  │  DoctrineCouchDBHandler.php
    │              │  │  DynamoDbHandler.php
    │              │  │  ElasticSearchHandler.php
    │              │  │  ErrorLogHandler.php
    │              │  │  FilterHandler.php
    │              │  │  FingersCrossedHandler.php
    │              │  │  FirePHPHandler.php
    │              │  │  FleepHookHandler.php
    │              │  │  FlowdockHandler.php
    │              │  │  FormattableHandlerInterface.php
    │              │  │  FormattableHandlerTrait.php
    │              │  │  GelfHandler.php
    │              │  │  GroupHandler.php
    │              │  │  HandlerInterface.php
    │              │  │  HandlerWrapper.php
    │              │  │  HipChatHandler.php
    │              │  │  IFTTTHandler.php
    │              │  │  InsightOpsHandler.php
    │              │  │  LogEntriesHandler.php
    │              │  │  LogglyHandler.php
    │              │  │  MailHandler.php
    │              │  │  MandrillHandler.php
    │              │  │  MissingExtensionException.php
    │              │  │  MongoDBHandler.php
    │              │  │  NativeMailerHandler.php
    │              │  │  NewRelicHandler.php
    │              │  │  NullHandler.php
    │              │  │  PHPConsoleHandler.php
    │              │  │  ProcessableHandlerInterface.php
    │              │  │  ProcessableHandlerTrait.php
    │              │  │  PsrHandler.php
    │              │  │  PushoverHandler.php
    │              │  │  RavenHandler.php
    │              │  │  RedisHandler.php
    │              │  │  RollbarHandler.php
    │              │  │  RotatingFileHandler.php
    │              │  │  SamplingHandler.php
    │              │  │  SlackbotHandler.php
    │              │  │  SlackHandler.php
    │              │  │  SlackWebhookHandler.php
    │              │  │  SocketHandler.php
    │              │  │  StreamHandler.php
    │              │  │  SwiftMailerHandler.php
    │              │  │  SyslogHandler.php
    │              │  │  SyslogUdpHandler.php
    │              │  │  TestHandler.php
    │              │  │  WhatFailureGroupHandler.php
    │              │  │  ZendMonitorHandler.php
    │              │  │  
    │              │  ├─Curl
    │              │  │      Util.php
    │              │  │      
    │              │  ├─FingersCrossed
    │              │  │      ActivationStrategyInterface.php
    │              │  │      ChannelLevelActivationStrategy.php
    │              │  │      ErrorLevelActivationStrategy.php
    │              │  │      
    │              │  ├─Slack
    │              │  │      SlackRecord.php
    │              │  │      
    │              │  └─SyslogUdp
    │              │          UdpSocket.php
    │              │          
    │              └─Processor
    │                      GitProcessor.php
    │                      IntrospectionProcessor.php
    │                      MemoryPeakUsageProcessor.php
    │                      MemoryProcessor.php
    │                      MemoryUsageProcessor.php
    │                      MercurialProcessor.php
    │                      ProcessIdProcessor.php
    │                      ProcessorInterface.php
    │                      PsrLogMessageProcessor.php
    │                      TagProcessor.php
    │                      UidProcessor.php
    │                      WebProcessor.php
    │                      
    ├─naixiaoxin
    │  └─think-wechat
    │      │  .gitignore
    │      │  composer.json
    │      │  README.md
    │      │  
    │      └─src
    │          │  CacheBridge.php
    │          │  config.php
    │          │  Facade.php
    │          │  helper.php
    │          │  
    │          ├─Behavior
    │          │      AppInit.php
    │          │      
    │          ├─Command
    │          │      Config.php
    │          │      
    │          └─Middleware
    │                  OauthMiddleware.php
    │                  
    ├─overtrue
    │  ├─socialite
    │  │  │  .gitignore
    │  │  │  .php_cs
    │  │  │  .travis.yml
    │  │  │  composer.json
    │  │  │  LICENSE.txt
    │  │  │  phpunit.xml
    │  │  │  README.md
    │  │  │  
    │  │  ├─.github
    │  │  │      FUNDING.yml
    │  │  │      
    │  │  ├─src
    │  │  │  │  AccessToken.php
    │  │  │  │  AccessTokenInterface.php
    │  │  │  │  AuthorizeFailedException.php
    │  │  │  │  Config.php
    │  │  │  │  FactoryInterface.php
    │  │  │  │  HasAttributes.php
    │  │  │  │  InvalidArgumentException.php
    │  │  │  │  InvalidStateException.php
    │  │  │  │  ProviderInterface.php
    │  │  │  │  SocialiteManager.php
    │  │  │  │  User.php
    │  │  │  │  UserInterface.php
    │  │  │  │  WeChatComponentInterface.php
    │  │  │  │  
    │  │  │  └─Providers
    │  │  │          AbstractProvider.php
    │  │  │          BaiduProvider.php
    │  │  │          DoubanProvider.php
    │  │  │          DouYinProvider.php
    │  │  │          FacebookProvider.php
    │  │  │          FeiShuProvider.php
    │  │  │          GitHubProvider.php
    │  │  │          GoogleProvider.php
    │  │  │          LinkedinProvider.php
    │  │  │          OutlookProvider.php
    │  │  │          QQProvider.php
    │  │  │          TaobaoProvider.php
    │  │  │          WeChatProvider.php
    │  │  │          WeiboProvider.php
    │  │  │          WeWorkProvider.php
    │  │  │          
    │  │  └─tests
    │  │      │  OAuthTest.php
    │  │      │  UserTest.php
    │  │      │  WechatProviderTest.php
    │  │      │  
    │  │      └─Providers
    │  │              WeWorkProviderTest.php
    │  │              
    │  └─wechat
    │      │  CHANGELOG.md
    │      │  composer.json
    │      │  CONTRIBUTING.md
    │      │  LICENSE
    │      │  README.md
    │      │  
    │      └─src
    │          │  Factory.php
    │          │  
    │          ├─BasicService
    │          │  │  Application.php
    │          │  │  
    │          │  ├─ContentSecurity
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Jssdk
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Media
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─QrCode
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  └─Url
    │          │          Client.php
    │          │          ServiceProvider.php
    │          │          
    │          ├─Kernel
    │          │  │  AccessToken.php
    │          │  │  BaseClient.php
    │          │  │  Config.php
    │          │  │  Encryptor.php
    │          │  │  Helpers.php
    │          │  │  ServerGuard.php
    │          │  │  ServiceContainer.php
    │          │  │  
    │          │  ├─Clauses
    │          │  │      Clause.php
    │          │  │      
    │          │  ├─Contracts
    │          │  │      AccessTokenInterface.php
    │          │  │      Arrayable.php
    │          │  │      EventHandlerInterface.php
    │          │  │      MediaInterface.php
    │          │  │      MessageInterface.php
    │          │  │      
    │          │  ├─Decorators
    │          │  │      FinallyResult.php
    │          │  │      TerminateResult.php
    │          │  │      
    │          │  ├─Events
    │          │  │      AccessTokenRefreshed.php
    │          │  │      ApplicationInitialized.php
    │          │  │      HttpResponseCreated.php
    │          │  │      ServerGuardResponseCreated.php
    │          │  │      
    │          │  ├─Exceptions
    │          │  │      BadRequestException.php
    │          │  │      DecryptException.php
    │          │  │      Exception.php
    │          │  │      HttpException.php
    │          │  │      InvalidArgumentException.php
    │          │  │      InvalidConfigException.php
    │          │  │      RuntimeException.php
    │          │  │      UnboundServiceException.php
    │          │  │      
    │          │  ├─Http
    │          │  │      Response.php
    │          │  │      StreamResponse.php
    │          │  │      
    │          │  ├─Log
    │          │  │      LogManager.php
    │          │  │      
    │          │  ├─Messages
    │          │  │      Article.php
    │          │  │      Card.php
    │          │  │      DeviceEvent.php
    │          │  │      DeviceText.php
    │          │  │      File.php
    │          │  │      Image.php
    │          │  │      Link.php
    │          │  │      Location.php
    │          │  │      Media.php
    │          │  │      Message.php
    │          │  │      MiniProgramPage.php
    │          │  │      Music.php
    │          │  │      News.php
    │          │  │      NewsItem.php
    │          │  │      Raw.php
    │          │  │      ShortVideo.php
    │          │  │      TaskCard.php
    │          │  │      Text.php
    │          │  │      TextCard.php
    │          │  │      Transfer.php
    │          │  │      Video.php
    │          │  │      Voice.php
    │          │  │      
    │          │  ├─Providers
    │          │  │      ConfigServiceProvider.php
    │          │  │      EventDispatcherServiceProvider.php
    │          │  │      ExtensionServiceProvider.php
    │          │  │      HttpClientServiceProvider.php
    │          │  │      LogServiceProvider.php
    │          │  │      RequestServiceProvider.php
    │          │  │      
    │          │  ├─Support
    │          │  │      AES.php
    │          │  │      Arr.php
    │          │  │      ArrayAccessible.php
    │          │  │      Collection.php
    │          │  │      File.php
    │          │  │      Helpers.php
    │          │  │      Str.php
    │          │  │      XML.php
    │          │  │      
    │          │  └─Traits
    │          │          HasAttributes.php
    │          │          HasHttpRequests.php
    │          │          InteractsWithCache.php
    │          │          Observable.php
    │          │          ResponseCastable.php
    │          │          
    │          ├─MicroMerchant
    │          │  │  Application.php
    │          │  │  
    │          │  ├─Base
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Certficates
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Kernel
    │          │  │  │  BaseClient.php
    │          │  │  │  
    │          │  │  └─Exceptions
    │          │  │          EncryptException.php
    │          │  │          InvalidExtensionException.php
    │          │  │          InvalidSignException.php
    │          │  │          
    │          │  ├─Material
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Media
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─MerchantConfig
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  └─Withdraw
    │          │          Client.php
    │          │          ServiceProvider.php
    │          │          
    │          ├─MiniProgram
    │          │  │  Application.php
    │          │  │  Encryptor.php
    │          │  │  
    │          │  ├─ActivityMessage
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─AppCode
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Auth
    │          │  │      AccessToken.php
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Base
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─CustomerService
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─DataCube
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Express
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Mall
    │          │  │      CartClient.php
    │          │  │      ForwardsMall.php
    │          │  │      MediaClient.php
    │          │  │      OrderClient.php
    │          │  │      ProductClient.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─NearbyPoi
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─OCR
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─OpenData
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Plugin
    │          │  │      Client.php
    │          │  │      DevClient.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Server
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Soter
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─SubscribeMessage
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─TemplateMessage
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  └─UniformMessage
    │          │          Client.php
    │          │          ServiceProvider.php
    │          │          
    │          ├─OfficialAccount
    │          │  │  Application.php
    │          │  │  
    │          │  ├─Auth
    │          │  │      AccessToken.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─AutoReply
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Base
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Broadcasting
    │          │  │      Client.php
    │          │  │      MessageBuilder.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Card
    │          │  │      BoardingPassClient.php
    │          │  │      Card.php
    │          │  │      Client.php
    │          │  │      CodeClient.php
    │          │  │      CoinClient.php
    │          │  │      GeneralCardClient.php
    │          │  │      GiftCardClient.php
    │          │  │      GiftCardOrderClient.php
    │          │  │      GiftCardPageClient.php
    │          │  │      InvoiceClient.php
    │          │  │      JssdkClient.php
    │          │  │      MeetingTicketClient.php
    │          │  │      MemberCardClient.php
    │          │  │      MovieTicketClient.php
    │          │  │      ServiceProvider.php
    │          │  │      SubMerchantClient.php
    │          │  │      
    │          │  ├─Comment
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─CustomerService
    │          │  │      Client.php
    │          │  │      Messenger.php
    │          │  │      ServiceProvider.php
    │          │  │      SessionClient.php
    │          │  │      
    │          │  ├─DataCube
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Device
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Goods
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Material
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Menu
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─OAuth
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─OCR
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─POI
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Semantic
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Server
    │          │  │  │  Guard.php
    │          │  │  │  ServiceProvider.php
    │          │  │  │  
    │          │  │  └─Handlers
    │          │  │          EchoStrHandler.php
    │          │  │          
    │          │  ├─ShakeAround
    │          │  │      Client.php
    │          │  │      DeviceClient.php
    │          │  │      GroupClient.php
    │          │  │      MaterialClient.php
    │          │  │      PageClient.php
    │          │  │      RelationClient.php
    │          │  │      ServiceProvider.php
    │          │  │      ShakeAround.php
    │          │  │      StatsClient.php
    │          │  │      
    │          │  ├─Store
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─TemplateMessage
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─User
    │          │  │      ServiceProvider.php
    │          │  │      TagClient.php
    │          │  │      UserClient.php
    │          │  │      
    │          │  └─WiFi
    │          │          CardClient.php
    │          │          Client.php
    │          │          DeviceClient.php
    │          │          ServiceProvider.php
    │          │          ShopClient.php
    │          │          
    │          ├─OpenPlatform
    │          │  │  Application.php
    │          │  │  
    │          │  ├─Auth
    │          │  │      AccessToken.php
    │          │  │      ServiceProvider.php
    │          │  │      VerifyTicket.php
    │          │  │      
    │          │  ├─Authorizer
    │          │  │  ├─Aggregate
    │          │  │  │  │  AggregateServiceProvider.php
    │          │  │  │  │  
    │          │  │  │  └─Account
    │          │  │  │          Client.php
    │          │  │  │          
    │          │  │  ├─Auth
    │          │  │  │      AccessToken.php
    │          │  │  │      
    │          │  │  ├─MiniProgram
    │          │  │  │  │  Application.php
    │          │  │  │  │  
    │          │  │  │  ├─Account
    │          │  │  │  │      Client.php
    │          │  │  │  │      ServiceProvider.php
    │          │  │  │  │      
    │          │  │  │  ├─Auth
    │          │  │  │  │      Client.php
    │          │  │  │  │      
    │          │  │  │  ├─Code
    │          │  │  │  │      Client.php
    │          │  │  │  │      ServiceProvider.php
    │          │  │  │  │      
    │          │  │  │  ├─Domain
    │          │  │  │  │      Client.php
    │          │  │  │  │      ServiceProvider.php
    │          │  │  │  │      
    │          │  │  │  ├─Setting
    │          │  │  │  │      Client.php
    │          │  │  │  │      ServiceProvider.php
    │          │  │  │  │      
    │          │  │  │  └─Tester
    │          │  │  │          Client.php
    │          │  │  │          ServiceProvider.php
    │          │  │  │          
    │          │  │  ├─OfficialAccount
    │          │  │  │  │  Application.php
    │          │  │  │  │  
    │          │  │  │  ├─Account
    │          │  │  │  │      Client.php
    │          │  │  │  │      
    │          │  │  │  ├─MiniProgram
    │          │  │  │  │      Client.php
    │          │  │  │  │      ServiceProvider.php
    │          │  │  │  │      
    │          │  │  │  └─OAuth
    │          │  │  │          ComponentDelegate.php
    │          │  │  │          
    │          │  │  └─Server
    │          │  │          Guard.php
    │          │  │          
    │          │  ├─Base
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─CodeTemplate
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Component
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  └─Server
    │          │      │  Guard.php
    │          │      │  ServiceProvider.php
    │          │      │  
    │          │      └─Handlers
    │          │              Authorized.php
    │          │              Unauthorized.php
    │          │              UpdateAuthorized.php
    │          │              VerifyTicketRefreshed.php
    │          │              
    │          ├─OpenWork
    │          │  │  Application.php
    │          │  │  
    │          │  ├─Auth
    │          │  │      AccessToken.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Corp
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─MiniProgram
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Provider
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Server
    │          │  │  │  Guard.php
    │          │  │  │  ServiceProvider.php
    │          │  │  │  
    │          │  │  └─Handlers
    │          │  │          EchoStrHandler.php
    │          │  │          
    │          │  ├─SuiteAuth
    │          │  │      AccessToken.php
    │          │  │      ServiceProvider.php
    │          │  │      SuiteTicket.php
    │          │  │      
    │          │  └─Work
    │          │      │  Application.php
    │          │      │  
    │          │      └─Auth
    │          │              AccessToken.php
    │          │              
    │          ├─Payment
    │          │  │  Application.php
    │          │  │  
    │          │  ├─Base
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Bill
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Coupon
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Jssdk
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Kernel
    │          │  │  │  BaseClient.php
    │          │  │  │  
    │          │  │  └─Exceptions
    │          │  │          InvalidSignException.php
    │          │  │          SandboxException.php
    │          │  │          
    │          │  ├─Merchant
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Notify
    │          │  │      Handler.php
    │          │  │      Paid.php
    │          │  │      Refunded.php
    │          │  │      Scanned.php
    │          │  │      
    │          │  ├─Order
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─ProfitSharing
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Redpack
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Refund
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Reverse
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Sandbox
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  ├─Security
    │          │  │      Client.php
    │          │  │      ServiceProvider.php
    │          │  │      
    │          │  └─Transfer
    │          │          Client.php
    │          │          ServiceProvider.php
    │          │          
    │          └─Work
    │              │  Application.php
    │              │  
    │              ├─Agent
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Auth
    │              │      AccessToken.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Base
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Chat
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Department
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─ExternalContact
    │              │      Client.php
    │              │      ContactWayClient.php
    │              │      MessageClient.php
    │              │      ServiceProvider.php
    │              │      StatisticsClient.php
    │              │      
    │              ├─GroupRobot
    │              │  │  Client.php
    │              │  │  Messenger.php
    │              │  │  ServiceProvider.php
    │              │  │  
    │              │  └─Messages
    │              │          Image.php
    │              │          Markdown.php
    │              │          Message.php
    │              │          News.php
    │              │          NewsItem.php
    │              │          Text.php
    │              │          
    │              ├─Invoice
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Jssdk
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Media
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Menu
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Message
    │              │      Client.php
    │              │      Messenger.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─MiniProgram
    │              │  │  Application.php
    │              │  │  
    │              │  └─Auth
    │              │          Client.php
    │              │          
    │              ├─OA
    │              │      Client.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─OAuth
    │              │      AccessTokenDelegate.php
    │              │      ServiceProvider.php
    │              │      
    │              ├─Server
    │              │  │  Guard.php
    │              │  │  ServiceProvider.php
    │              │  │  
    │              │  └─Handlers
    │              │          EchoStrHandler.php
    │              │          
    │              └─User
    │                      Client.php
    │                      ServiceProvider.php
    │                      TagClient.php
    │                      
    ├─pimple
    │  └─pimple
    │      │  .gitignore
    │      │  .travis.yml
    │      │  CHANGELOG
    │      │  composer.json
    │      │  LICENSE
    │      │  phpunit.xml.dist
    │      │  README.rst
    │      │  
    │      ├─ext
    │      │  └─pimple
    │      │      │  .gitignore
    │      │      │  config.m4
    │      │      │  config.w32
    │      │      │  php_pimple.h
    │      │      │  pimple.c
    │      │      │  pimple_compat.h
    │      │      │  README.md
    │      │      │  
    │      │      └─tests
    │      │              001.phpt
    │      │              002.phpt
    │      │              003.phpt
    │      │              004.phpt
    │      │              005.phpt
    │      │              006.phpt
    │      │              007.phpt
    │      │              008.phpt
    │      │              009.phpt
    │      │              010.phpt
    │      │              011.phpt
    │      │              012.phpt
    │      │              013.phpt
    │      │              014.phpt
    │      │              015.phpt
    │      │              016.phpt
    │      │              017.phpt
    │      │              017_1.phpt
    │      │              018.phpt
    │      │              019.phpt
    │      │              bench.phpb
    │      │              bench_shared.phpb
    │      │              
    │      └─src
    │          └─Pimple
    │              │  Container.php
    │              │  ServiceIterator.php
    │              │  ServiceProviderInterface.php
    │              │  
    │              ├─Exception
    │              │      ExpectedInvokableException.php
    │              │      FrozenServiceException.php
    │              │      InvalidServiceIdentifierException.php
    │              │      UnknownIdentifierException.php
    │              │      
    │              ├─Psr11
    │              │      Container.php
    │              │      ServiceLocator.php
    │              │      
    │              └─Tests
    │                  │  PimpleServiceProviderInterfaceTest.php
    │                  │  PimpleTest.php
    │                  │  ServiceIteratorTest.php
    │                  │  
    │                  ├─Fixtures
    │                  │      Invokable.php
    │                  │      NonInvokable.php
    │                  │      PimpleServiceProvider.php
    │                  │      Service.php
    │                  │      
    │                  └─Psr11
    │                          ContainerTest.php
    │                          ServiceLocatorTest.php
    │                          
    ├─psr
    │  ├─cache
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  LICENSE.txt
    │  │  │  README.md
    │  │  │  
    │  │  └─src
    │  │          CacheException.php
    │  │          CacheItemInterface.php
    │  │          CacheItemPoolInterface.php
    │  │          InvalidArgumentException.php
    │  │          
    │  ├─container
    │  │  │  .gitignore
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  
    │  │  └─src
    │  │          ContainerExceptionInterface.php
    │  │          ContainerInterface.php
    │  │          NotFoundExceptionInterface.php
    │  │          
    │  ├─http-message
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  
    │  │  └─src
    │  │          MessageInterface.php
    │  │          RequestInterface.php
    │  │          ResponseInterface.php
    │  │          ServerRequestInterface.php
    │  │          StreamInterface.php
    │  │          UploadedFileInterface.php
    │  │          UriInterface.php
    │  │          
    │  ├─log
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  
    │  │  └─Psr
    │  │      └─Log
    │  │          │  AbstractLogger.php
    │  │          │  InvalidArgumentException.php
    │  │          │  LoggerAwareInterface.php
    │  │          │  LoggerAwareTrait.php
    │  │          │  LoggerInterface.php
    │  │          │  LoggerTrait.php
    │  │          │  LogLevel.php
    │  │          │  NullLogger.php
    │  │          │  
    │  │          └─Test
    │  │                  DummyTest.php
    │  │                  LoggerInterfaceTest.php
    │  │                  TestLogger.php
    │  │                  
    │  └─simple-cache
    │      │  .editorconfig
    │      │  composer.json
    │      │  LICENSE.md
    │      │  README.md
    │      │  
    │      └─src
    │              CacheException.php
    │              CacheInterface.php
    │              InvalidArgumentException.php
    │              
    ├─ralouphie
    │  └─getallheaders
    │      │  composer.json
    │      │  LICENSE
    │      │  README.md
    │      │  
    │      └─src
    │              getallheaders.php
    │              
    ├─symfony
    │  ├─cache
    │  │  │  CacheItem.php
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  DoctrineProvider.php
    │  │  │  LICENSE
    │  │  │  LockRegistry.php
    │  │  │  PruneableInterface.php
    │  │  │  Psr16Cache.php
    │  │  │  README.md
    │  │  │  ResettableInterface.php
    │  │  │  
    │  │  ├─Adapter
    │  │  │      AbstractAdapter.php
    │  │  │      AbstractTagAwareAdapter.php
    │  │  │      AdapterInterface.php
    │  │  │      ApcuAdapter.php
    │  │  │      ArrayAdapter.php
    │  │  │      ChainAdapter.php
    │  │  │      DoctrineAdapter.php
    │  │  │      FilesystemAdapter.php
    │  │  │      FilesystemTagAwareAdapter.php
    │  │  │      MemcachedAdapter.php
    │  │  │      NullAdapter.php
    │  │  │      PdoAdapter.php
    │  │  │      PhpArrayAdapter.php
    │  │  │      PhpFilesAdapter.php
    │  │  │      ProxyAdapter.php
    │  │  │      Psr16Adapter.php
    │  │  │      RedisAdapter.php
    │  │  │      RedisTagAwareAdapter.php
    │  │  │      SimpleCacheAdapter.php
    │  │  │      TagAwareAdapter.php
    │  │  │      TagAwareAdapterInterface.php
    │  │  │      TraceableAdapter.php
    │  │  │      TraceableTagAwareAdapter.php
    │  │  │      
    │  │  ├─DataCollector
    │  │  │      CacheDataCollector.php
    │  │  │      
    │  │  ├─DependencyInjection
    │  │  │      CacheCollectorPass.php
    │  │  │      CachePoolClearerPass.php
    │  │  │      CachePoolPass.php
    │  │  │      CachePoolPrunerPass.php
    │  │  │      
    │  │  ├─Exception
    │  │  │      CacheException.php
    │  │  │      InvalidArgumentException.php
    │  │  │      LogicException.php
    │  │  │      
    │  │  ├─Marshaller
    │  │  │      DefaultMarshaller.php
    │  │  │      DeflateMarshaller.php
    │  │  │      MarshallerInterface.php
    │  │  │      TagAwareMarshaller.php
    │  │  │      
    │  │  ├─Simple
    │  │  │      AbstractCache.php
    │  │  │      ApcuCache.php
    │  │  │      ArrayCache.php
    │  │  │      ChainCache.php
    │  │  │      DoctrineCache.php
    │  │  │      FilesystemCache.php
    │  │  │      MemcachedCache.php
    │  │  │      NullCache.php
    │  │  │      PdoCache.php
    │  │  │      PhpArrayCache.php
    │  │  │      PhpFilesCache.php
    │  │  │      Psr6Cache.php
    │  │  │      RedisCache.php
    │  │  │      TraceableCache.php
    │  │  │      
    │  │  └─Traits
    │  │          AbstractAdapterTrait.php
    │  │          AbstractTrait.php
    │  │          ApcuTrait.php
    │  │          ArrayTrait.php
    │  │          ContractsTrait.php
    │  │          DoctrineTrait.php
    │  │          FilesystemCommonTrait.php
    │  │          FilesystemTrait.php
    │  │          MemcachedTrait.php
    │  │          PdoTrait.php
    │  │          PhpArrayTrait.php
    │  │          PhpFilesTrait.php
    │  │          ProxyTrait.php
    │  │          RedisClusterProxy.php
    │  │          RedisProxy.php
    │  │          RedisTrait.php
    │  │          
    │  ├─cache-contracts
    │  │      .gitignore
    │  │      CacheInterface.php
    │  │      CacheTrait.php
    │  │      CallbackInterface.php
    │  │      composer.json
    │  │      ItemInterface.php
    │  │      LICENSE
    │  │      README.md
    │  │      TagAwareCacheInterface.php
    │  │      
    │  ├─event-dispatcher
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  Event.php
    │  │  │  EventDispatcher.php
    │  │  │  EventDispatcherInterface.php
    │  │  │  EventSubscriberInterface.php
    │  │  │  GenericEvent.php
    │  │  │  ImmutableEventDispatcher.php
    │  │  │  LegacyEventDispatcherProxy.php
    │  │  │  LegacyEventProxy.php
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  
    │  │  ├─Debug
    │  │  │      TraceableEventDispatcher.php
    │  │  │      TraceableEventDispatcherInterface.php
    │  │  │      WrappedListener.php
    │  │  │      
    │  │  └─DependencyInjection
    │  │          AddEventAliasesPass.php
    │  │          RegisterListenersPass.php
    │  │          
    │  ├─event-dispatcher-contracts
    │  │      .gitignore
    │  │      composer.json
    │  │      Event.php
    │  │      EventDispatcherInterface.php
    │  │      LICENSE
    │  │      README.md
    │  │      
    │  ├─http-foundation
    │  │  │  AcceptHeader.php
    │  │  │  AcceptHeaderItem.php
    │  │  │  ApacheRequest.php
    │  │  │  BinaryFileResponse.php
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  Cookie.php
    │  │  │  ExpressionRequestMatcher.php
    │  │  │  FileBag.php
    │  │  │  HeaderBag.php
    │  │  │  HeaderUtils.php
    │  │  │  IpUtils.php
    │  │  │  JsonResponse.php
    │  │  │  LICENSE
    │  │  │  ParameterBag.php
    │  │  │  README.md
    │  │  │  RedirectResponse.php
    │  │  │  Request.php
    │  │  │  RequestMatcher.php
    │  │  │  RequestMatcherInterface.php
    │  │  │  RequestStack.php
    │  │  │  Response.php
    │  │  │  ResponseHeaderBag.php
    │  │  │  ServerBag.php
    │  │  │  StreamedResponse.php
    │  │  │  UrlHelper.php
    │  │  │  
    │  │  ├─Exception
    │  │  │      ConflictingHeadersException.php
    │  │  │      RequestExceptionInterface.php
    │  │  │      SuspiciousOperationException.php
    │  │  │      
    │  │  ├─File
    │  │  │  │  File.php
    │  │  │  │  Stream.php
    │  │  │  │  UploadedFile.php
    │  │  │  │  
    │  │  │  ├─Exception
    │  │  │  │      AccessDeniedException.php
    │  │  │  │      CannotWriteFileException.php
    │  │  │  │      ExtensionFileException.php
    │  │  │  │      FileException.php
    │  │  │  │      FileNotFoundException.php
    │  │  │  │      FormSizeFileException.php
    │  │  │  │      IniSizeFileException.php
    │  │  │  │      NoFileException.php
    │  │  │  │      NoTmpDirFileException.php
    │  │  │  │      PartialFileException.php
    │  │  │  │      UnexpectedTypeException.php
    │  │  │  │      UploadException.php
    │  │  │  │      
    │  │  │  └─MimeType
    │  │  │          ExtensionGuesser.php
    │  │  │          ExtensionGuesserInterface.php
    │  │  │          FileBinaryMimeTypeGuesser.php
    │  │  │          FileinfoMimeTypeGuesser.php
    │  │  │          MimeTypeExtensionGuesser.php
    │  │  │          MimeTypeGuesser.php
    │  │  │          MimeTypeGuesserInterface.php
    │  │  │          
    │  │  ├─Session
    │  │  │  │  Session.php
    │  │  │  │  SessionBagInterface.php
    │  │  │  │  SessionBagProxy.php
    │  │  │  │  SessionInterface.php
    │  │  │  │  SessionUtils.php
    │  │  │  │  
    │  │  │  ├─Attribute
    │  │  │  │      AttributeBag.php
    │  │  │  │      AttributeBagInterface.php
    │  │  │  │      NamespacedAttributeBag.php
    │  │  │  │      
    │  │  │  ├─Flash
    │  │  │  │      AutoExpireFlashBag.php
    │  │  │  │      FlashBag.php
    │  │  │  │      FlashBagInterface.php
    │  │  │  │      
    │  │  │  └─Storage
    │  │  │      │  MetadataBag.php
    │  │  │      │  MockArraySessionStorage.php
    │  │  │      │  MockFileSessionStorage.php
    │  │  │      │  NativeSessionStorage.php
    │  │  │      │  PhpBridgeSessionStorage.php
    │  │  │      │  SessionStorageInterface.php
    │  │  │      │  
    │  │  │      ├─Handler
    │  │  │      │      AbstractSessionHandler.php
    │  │  │      │      MemcachedSessionHandler.php
    │  │  │      │      MigratingSessionHandler.php
    │  │  │      │      MongoDbSessionHandler.php
    │  │  │      │      NativeFileSessionHandler.php
    │  │  │      │      NullSessionHandler.php
    │  │  │      │      PdoSessionHandler.php
    │  │  │      │      RedisSessionHandler.php
    │  │  │      │      SessionHandlerFactory.php
    │  │  │      │      StrictSessionHandler.php
    │  │  │      │      
    │  │  │      └─Proxy
    │  │  │              AbstractProxy.php
    │  │  │              SessionHandlerProxy.php
    │  │  │              
    │  │  └─Test
    │  │      └─Constraint
    │  │              RequestAttributeValueSame.php
    │  │              ResponseCookieValueSame.php
    │  │              ResponseHasCookie.php
    │  │              ResponseHasHeader.php
    │  │              ResponseHeaderSame.php
    │  │              ResponseIsRedirected.php
    │  │              ResponseIsSuccessful.php
    │  │              ResponseStatusCodeSame.php
    │  │              
    │  ├─mime
    │  │  │  Address.php
    │  │  │  BodyRendererInterface.php
    │  │  │  CHANGELOG.md
    │  │  │  CharacterStream.php
    │  │  │  composer.json
    │  │  │  Email.php
    │  │  │  FileBinaryMimeTypeGuesser.php
    │  │  │  FileinfoMimeTypeGuesser.php
    │  │  │  LICENSE
    │  │  │  Message.php
    │  │  │  MessageConverter.php
    │  │  │  MimeTypeGuesserInterface.php
    │  │  │  MimeTypes.php
    │  │  │  MimeTypesInterface.php
    │  │  │  RawMessage.php
    │  │  │  README.md
    │  │  │  
    │  │  ├─Crypto
    │  │  │      SMime.php
    │  │  │      SMimeEncrypter.php
    │  │  │      SMimeSigner.php
    │  │  │      
    │  │  ├─DependencyInjection
    │  │  │      AddMimeTypeGuesserPass.php
    │  │  │      
    │  │  ├─Encoder
    │  │  │      AddressEncoderInterface.php
    │  │  │      Base64ContentEncoder.php
    │  │  │      Base64Encoder.php
    │  │  │      Base64MimeHeaderEncoder.php
    │  │  │      ContentEncoderInterface.php
    │  │  │      EightBitContentEncoder.php
    │  │  │      EncoderInterface.php
    │  │  │      IdnAddressEncoder.php
    │  │  │      MimeHeaderEncoderInterface.php
    │  │  │      QpContentEncoder.php
    │  │  │      QpEncoder.php
    │  │  │      QpMimeHeaderEncoder.php
    │  │  │      Rfc2231Encoder.php
    │  │  │      
    │  │  ├─Exception
    │  │  │      AddressEncoderException.php
    │  │  │      ExceptionInterface.php
    │  │  │      InvalidArgumentException.php
    │  │  │      LogicException.php
    │  │  │      RfcComplianceException.php
    │  │  │      RuntimeException.php
    │  │  │      
    │  │  ├─Header
    │  │  │      AbstractHeader.php
    │  │  │      DateHeader.php
    │  │  │      HeaderInterface.php
    │  │  │      Headers.php
    │  │  │      IdentificationHeader.php
    │  │  │      MailboxHeader.php
    │  │  │      MailboxListHeader.php
    │  │  │      ParameterizedHeader.php
    │  │  │      PathHeader.php
    │  │  │      UnstructuredHeader.php
    │  │  │      
    │  │  ├─Part
    │  │  │  │  AbstractMultipartPart.php
    │  │  │  │  AbstractPart.php
    │  │  │  │  DataPart.php
    │  │  │  │  MessagePart.php
    │  │  │  │  SMimePart.php
    │  │  │  │  TextPart.php
    │  │  │  │  
    │  │  │  └─Multipart
    │  │  │          AlternativePart.php
    │  │  │          DigestPart.php
    │  │  │          FormDataPart.php
    │  │  │          MixedPart.php
    │  │  │          RelatedPart.php
    │  │  │          
    │  │  ├─Resources
    │  │  │  └─bin
    │  │  │          update_mime_types.php
    │  │  │          
    │  │  └─Test
    │  │      └─Constraint
    │  │              EmailAddressContains.php
    │  │              EmailAttachmentCount.php
    │  │              EmailHasHeader.php
    │  │              EmailHeaderSame.php
    │  │              EmailHtmlBodyContains.php
    │  │              EmailTextBodyContains.php
    │  │              
    │  ├─polyfill-intl-idn
    │  │  │  bootstrap.php
    │  │  │  bootstrap80.php
    │  │  │  composer.json
    │  │  │  Idn.php
    │  │  │  Info.php
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  
    │  │  └─Resources
    │  │      └─unidata
    │  │              deviation.php
    │  │              disallowed.php
    │  │              DisallowedRanges.php
    │  │              disallowed_STD3_mapped.php
    │  │              disallowed_STD3_valid.php
    │  │              ignored.php
    │  │              mapped.php
    │  │              Regex.php
    │  │              virama.php
    │  │              
    │  ├─polyfill-intl-normalizer
    │  │  │  bootstrap.php
    │  │  │  bootstrap80.php
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  Normalizer.php
    │  │  │  README.md
    │  │  │  
    │  │  └─Resources
    │  │      ├─stubs
    │  │      │      Normalizer.php
    │  │      │      
    │  │      └─unidata
    │  │              canonicalComposition.php
    │  │              canonicalDecomposition.php
    │  │              combiningClass.php
    │  │              compatibilityDecomposition.php
    │  │              
    │  ├─polyfill-mbstring
    │  │  │  bootstrap.php
    │  │  │  bootstrap80.php
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  Mbstring.php
    │  │  │  README.md
    │  │  │  
    │  │  └─Resources
    │  │      └─unidata
    │  │              lowerCase.php
    │  │              titleCaseRegexp.php
    │  │              upperCase.php
    │  │              
    │  ├─polyfill-php72
    │  │      bootstrap.php
    │  │      composer.json
    │  │      LICENSE
    │  │      Php72.php
    │  │      README.md
    │  │      
    │  ├─polyfill-php80
    │  │  │  bootstrap.php
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  Php80.php
    │  │  │  README.md
    │  │  │  
    │  │  └─Resources
    │  │      └─stubs
    │  │              Attribute.php
    │  │              Stringable.php
    │  │              UnhandledMatchError.php
    │  │              ValueError.php
    │  │              
    │  ├─psr-http-message-bridge
    │  │  │  .gitignore
    │  │  │  .php_cs.dist
    │  │  │  .travis.yml
    │  │  │  CHANGELOG.md
    │  │  │  composer.json
    │  │  │  HttpFoundationFactoryInterface.php
    │  │  │  HttpMessageFactoryInterface.php
    │  │  │  LICENSE
    │  │  │  phpunit.xml.dist
    │  │  │  README.md
    │  │  │  
    │  │  ├─Factory
    │  │  │      DiactorosFactory.php
    │  │  │      HttpFoundationFactory.php
    │  │  │      PsrHttpFactory.php
    │  │  │      UploadedFile.php
    │  │  │      
    │  │  └─Tests
    │  │      ├─Factory
    │  │      │      AbstractHttpMessageFactoryTest.php
    │  │      │      DiactorosFactoryTest.php
    │  │      │      HttpFoundationFactoryTest.php
    │  │      │      PsrHttpFactoryTest.php
    │  │      │      
    │  │      ├─Fixtures
    │  │      │      Message.php
    │  │      │      Response.php
    │  │      │      ServerRequest.php
    │  │      │      Stream.php
    │  │      │      UploadedFile.php
    │  │      │      Uri.php
    │  │      │      
    │  │      └─Functional
    │  │              CovertTest.php
    │  │              
    │  ├─service-contracts
    │  │  │  .gitignore
    │  │  │  composer.json
    │  │  │  LICENSE
    │  │  │  README.md
    │  │  │  ResetInterface.php
    │  │  │  ServiceLocatorTrait.php
    │  │  │  ServiceProviderInterface.php
    │  │  │  ServiceSubscriberInterface.php
    │  │  │  ServiceSubscriberTrait.php
    │  │  │  
    │  │  └─Test
    │  │          ServiceLocatorTest.php
    │  │          
    │  └─var-exporter
    │      │  CHANGELOG.md
    │      │  composer.json
    │      │  Instantiator.php
    │      │  LICENSE
    │      │  README.md
    │      │  VarExporter.php
    │      │  
    │      ├─Exception
    │      │      ClassNotFoundException.php
    │      │      ExceptionInterface.php
    │      │      NotInstantiableTypeException.php
    │      │      
    │      └─Internal
    │              Exporter.php
    │              Hydrator.php
    │              Reference.php
    │              Registry.php
    │              Values.php
    │              
    ├─topthink
    │  ├─think-installer
    │  │  │  .gitignore
    │  │  │  composer.json
    │  │  │  
    │  │  └─src
    │  │          LibraryInstaller.php
    │  │          Plugin.php
    │  │          Promise.php
    │  │          ThinkExtend.php
    │  │          ThinkFramework.php
    │  │          ThinkTesting.php
    │  │          
    │  └─think-worker
    │      │  composer.json
    │      │  LICENSE
    │      │  README.md
    │      │  
    │      └─src
    │          │  Application.php
    │          │  command.php
    │          │  Cookie.php
    │          │  Events.php
    │          │  Http.php
    │          │  Server.php
    │          │  Session.php
    │          │  
    │          ├─command
    │          │      GatewayWorker.php
    │          │      Server.php
    │          │      Worker.php
    │          │      
    │          ├─config
    │          │      gateway.php
    │          │      server.php
    │          │      worker.php
    │          │      
    │          ├─facade
    │          │      Application.php
    │          │      Worker.php
    │          │      
    │          └─log
    │                  File.php
    │                  
    └─workerman
        ├─gateway-worker
        │  │  .gitignore
        │  │  composer.json
        │  │  MIT-LICENSE.txt
        │  │  README.md
        │  │  
        │  └─src
        │      │  BusinessWorker.php
        │      │  Gateway.php
        │      │  Register.php
        │      │  
        │      ├─Lib
        │      │      Context.php
        │      │      Db.php
        │      │      DbConnection.php
        │      │      Gateway.php
        │      │      
        │      └─Protocols
        │              GatewayProtocol.php
        │              
        └─workerman
            │  .gitignore
            │  Autoloader.php
            │  composer.json
            │  MIT-LICENSE.txt
            │  README.md
            │  WebServer.php
            │  Worker.php
            │  
            ├─Connection
            │      AsyncTcpConnection.php
            │      AsyncUdpConnection.php
            │      ConnectionInterface.php
            │      TcpConnection.php
            │      UdpConnection.php
            │      
            ├─Events
            │  │  Ev.php
            │  │  Event.php
            │  │  EventInterface.php
            │  │  Libevent.php
            │  │  Select.php
            │  │  Swoole.php
            │  │  
            │  └─React
            │          Base.php
            │          ExtEventLoop.php
            │          ExtLibEventLoop.php
            │          StreamSelectLoop.php
            │          
            ├─Lib
            │      Constants.php
            │      Timer.php
            │      
            └─Protocols
                │  Frame.php
                │  Http.php
                │  ProtocolInterface.php
                │  Text.php
                │  Websocket.php
                │  Ws.php
                │  
                └─Http
                        mime.types
                        
