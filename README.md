# 前言
本项目仅用于学术研究,请勿用于商业用途否则后果自负.
 
## 说明
商品信息溯源系统，后端采用<a href="https://github.com/lmxdawn">lmxdawn作者</a>的vue+TP进行二次封装,
本项目二次封装项目说明<a href="./README">README</a>

项目含有三端(后端接口,后台管理系统,前端UNIAPP-H5)

后端:<a href="https://github.com/LeonGinger/sy-tp-api">https://github.com/LeonGinger/sy-tp-api</a>

后台:<a href="https://github.com/LeonGinger/sy-tp-html">https://github.com/LeonGinger/sy-tp-html</a>

前端H5:<a href="https://github.com/LeonGinger/sy-h5-html">https://github.com/LeonGinger/sy-h5-html</a>

本项目开发作者:

@Author:<a href="https://gitee.com/LeonJay">LeonJay</a>

@Author:<a href="https://gitee.com/just-be">just-be</a>



# 以下是原作者lmxdawn项目说明

**项目前端地址：** <a href="https://github.com/lmxdawn/vue-admin-html" target="_blank">https://github.com/lmxdawn/vue-admin-html</a>

**项目后端地址：** <a href="https://github.com/lmxdawn/vue-admin-php" target="_blank">https://github.com/lmxdawn/vue-admin-php</a>

# 欢迎 star

# 整体效果

![donate](https://lmxdawn.github.io/images/show-how1.jpg)

# vue-admin-php

> Vue-cli3.0 + Element UI + ThinkPHP5.1 + RBAC权限 + 响应式的后台管理系统

# 一键操作包 <a href="https://pan.baidu.com/s/1og4fb7FePOQ1HIDOcBqmVw">点击下载</a>

1. 集成环境搭建: windows 上面建议用 phpstudy ,其它环境自行百度
2. 把两个文件放到网站根目录
3. 把MySQL的root密码改为 root, 再新建数据库 vue-admin ,再把vue-admin.sql 文件导入到MySQL
4. 打开浏览器 输入 http://localhost/vue-admin-html/dist/index.html


## 功能 ##
- [x] 管理员登录
- [x] 登录
- [x] 修改密码
- [x] 角色管理
- [x] 权限管理
- [x] 401/404错误页面
- [x] 动态面包屑
- [x] 动态侧边栏
- [x] 广告管理


## 安装步骤 ##

	git clone https://github.com/lmxdawn/vue-admin-php.git      // 把模板下载到本地
	cd vue-admin-php    // 进入模板目录
	composer install         // 安装项目依赖，等待安装完成之后

## 一些注意点 ##
1. 用的是 tp5.1 版本，具体文档 <a href="https://www.kancloud.cn/manual/thinkphp5_1/353946">点击查看</a>
2. 安装好后请把 composer.json 里面的 "topthink/framework": "5.1.*"  * 号 改成具体的某个版本