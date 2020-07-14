
HOW TO USE Glup Tools
--------------------------------

1. 安装前端依赖

> 你需要保证本地安装了 node 环境，此时可以使用 npm ，如果需要使用 cnpm 需要安装阿里的 npm 镜像，如果需要使用 yarn 则需要使用 npm 安装 yarn

```
npm install
 (or) 
cnpm install
 (or) 
yarn install
```

2. 你需要保证你本地安装了 gulp 工具

```
npm install gulp-cli -g
 (or) 
cnpm install gulp-cli -g
```

3. 你可以看到本项目有如下几个命令
```
gulp --tasks   // 查看可执行的本目录集合

gulp watch     // 启用 watch 模式，当文件发生变动的时候则会触发
gulp sass      // 编译 scss 到 css目录下
gulp imagemin  // 图片压缩并迁移到 images 目录
gulp eslint    // js 规范验证
```

DEVELOPMENT STANDARD
--------------------------------

1. 全部前端开发都应该在 [src] 目录下
2. 静态资源科放置 [assets] 中，如果需要构建的静态资源，则需要放到 [src] 下，比如图片需要做压缩的话，需要放到 [src/images] 中
3. css 开发使用 scss
4. scss 规范中规定 [_] 下划线开始的文件为 scss 的配置文件